<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Review;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class CustomerController extends Controller
{
    /**
     * Display a listing of customer users with period analytics, search, filters, and pagination.
     */
    public function index(Request $request): View
    {
        // Parse period filter (day, month, year)
        $periodInfo = $this->getPeriodDateRange($request);
        $startDate = $periodInfo['startDate'];
        $endDate = $periodInfo['endDate'];

        // Period-filtered Customer Analytics Statistics from REAL Database
        $totalNewCustomers = User::where('role', 'customer')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->count();

        $activeCustomers = User::where('role', 'customer')
            ->where('status', 'active')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->count();

        $verifiedCustomers = User::where('role', 'customer')
            ->whereNotNull('email_verified_at')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->count();

        $blockedCustomers = User::where('role', 'customer')
            ->where('status', 'blocked')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->count();

        // REAL Order & Revenue Analytics from Database for the filtered period
        $totalOrders = Order::whereBetween('created_at', [$startDate, $endDate])->count();

        $totalRevenue = Order::whereBetween('created_at', [$startDate, $endDate])
            ->where(function ($q) {
                $q->where('order_status', 'completed')
                  ->orWhere('payment_status', 'paid');
            })->sum('grand_total');

        $totalProductsPurchased = OrderItem::whereHas('order', function ($q) use ($startDate, $endDate) {
            $q->whereBetween('created_at', [$startDate, $endDate]);
        })->sum('qty');

        $totalRatings = Review::whereBetween('created_at', [$startDate, $endDate])->count();

        // Query Builder for Customer List with eager counts
        $query = User::where('role', 'customer')
            ->withCount(['orders', 'reviews']);

        // Apply period filter to customer table list if period parameter is explicitly passed
        if ($request->filled('period')) {
            $query->whereBetween('created_at', [$startDate, $endDate]);
        }

        // Check phone column name
        $hasPhoneColumn = Schema::hasColumn('users', 'phone') ? 'phone' : (Schema::hasColumn('users', 'phone_number') ? 'phone_number' : null);

        // Search by Name, Email, or Phone
        if ($request->filled('search')) {
            $search = trim($request->input('search'));
            $query->where(function ($q) use ($search, $hasPhoneColumn) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");

                if ($hasPhoneColumn) {
                    $q->orWhere($hasPhoneColumn, 'like', "%{$search}%");
                }
            });
        }

        // Filter by Status
        if ($request->filled('status') && in_array($request->input('status'), ['active', 'inactive', 'blocked'])) {
            $query->where('status', $request->input('status'));
        }

        // Filter by Verification Status
        if ($request->filled('verified')) {
            if ($request->input('verified') === 'verified') {
                $query->whereNotNull('email_verified_at');
            } elseif ($request->input('verified') === 'unverified') {
                $query->whereNull('email_verified_at');
            }
        }

        // Sorting
        $sort = $request->input('sort', 'latest');
        switch ($sort) {
            case 'oldest':
                $query->orderBy('created_at', 'asc');
                break;
            case 'name_asc':
                $query->orderBy('name', 'asc');
                break;
            case 'name_desc':
                $query->orderBy('name', 'desc');
                break;
            case 'orders_desc':
                $query->orderBy('orders_count', 'desc');
                break;
            case 'latest':
            default:
                $query->orderBy('created_at', 'desc');
                break;
        }

        $customers = $query->paginate(10)->withQueryString();

        return view('admin.customers.index', compact(
            'customers',
            'periodInfo',
            'totalNewCustomers',
            'activeCustomers',
            'verifiedCustomers',
            'blockedCustomers',
            'totalOrders',
            'totalRevenue',
            'totalProductsPurchased',
            'totalRatings'
        ));
    }

    /**
     * Display the specified customer detail with period analytics sourced 100% from real DB.
     */
    public function show(User $customer, Request $request): View
    {
        // Security check: Ensure the requested user is strictly a customer
        if (!$customer->isCustomer()) {
            abort(404);
        }

        // Parse period filter for customer detail analytics
        $periodInfo = $this->getPeriodDateRange($request);
        $startDate = $periodInfo['startDate'];
        $endDate = $periodInfo['endDate'];

        // REAL Purchase Summary Metrics for this customer
        $totalOrders = $customer->orders()
            ->whereBetween('created_at', [$startDate, $endDate])
            ->count();

        $totalSpent = $customer->orders()
            ->whereBetween('created_at', [$startDate, $endDate])
            ->where(function ($q) {
                $q->where('order_status', 'completed')
                  ->orWhere('payment_status', 'paid');
            })->sum('grand_total');

        $averageOrder = $totalOrders > 0 ? ($totalSpent / $totalOrders) : 0;

        $largestOrder = $customer->orders()
            ->whereBetween('created_at', [$startDate, $endDate])
            ->max('grand_total') ?? 0;

        $lastOrderRecord = $customer->orders()
            ->whereBetween('created_at', [$startDate, $endDate])
            ->latest()
            ->first();
        $lastOrderDate = $lastOrderRecord ? ($lastOrderRecord->order_date ?? $lastOrderRecord->created_at) : null;

        // Calculate REAL Customer Level tier based on lifetime completed orders and spending
        $lifetimeOrders = $customer->orders()->count();
        $lifetimeSpent = $customer->orders()
            ->where(function ($q) {
                $q->where('order_status', 'completed')
                  ->orWhere('payment_status', 'paid');
            })->sum('grand_total');

        if ($lifetimeOrders >= 10 || $lifetimeSpent >= 500000) {
            $customerLevel = 'Gold Customer';
        } elseif ($lifetimeOrders >= 4 || $lifetimeSpent >= 200000) {
            $customerLevel = 'Silver Customer';
        } elseif ($lifetimeOrders >= 1) {
            $customerLevel = 'Bronze Customer';
        } else {
            $customerLevel = 'New Customer';
        }

        // REAL Product Preference Summaries for this customer
        $mostPurchasedOrderItem = OrderItem::whereHas('order', function ($q) use ($customer, $startDate, $endDate) {
            $q->where('user_id', $customer->id)
              ->whereBetween('created_at', [$startDate, $endDate]);
        })
        ->select('product_id', DB::raw('SUM(qty) as total_qty'))
        ->groupBy('product_id')
        ->orderByDesc('total_qty')
        ->with('product')
        ->first();

        $mostPurchasedProduct = $mostPurchasedOrderItem && $mostPurchasedOrderItem->product ? $mostPurchasedOrderItem->product->name : null;

        $favoriteCategoryItem = OrderItem::whereHas('order', function ($q) use ($customer, $startDate, $endDate) {
            $q->where('user_id', $customer->id)
              ->whereBetween('created_at', [$startDate, $endDate]);
        })
        ->whereHas('product.category')
        ->join('products', 'order_items.product_id', '=', 'products.id')
        ->join('categories', 'products.category_id', '=', 'categories.id')
        ->select('categories.name as category_name', DB::raw('SUM(order_items.qty) as total_qty'))
        ->groupBy('categories.id', 'categories.name')
        ->orderByDesc('total_qty')
        ->first();

        $favoriteCategory = $favoriteCategoryItem ? $favoriteCategoryItem->category_name : null;

        // REAL Activity Metrics from database
        $ratingCount = $customer->reviews()->whereBetween('created_at', [$startDate, $endDate])->count();
        $reportCount = $customer->reports()->whereBetween('created_at', [$startDate, $endDate])->count();

        // REAL Recent Orders collection
        $recentOrders = $customer->orders()
            ->with(['items.product', 'payment'])
            ->whereBetween('created_at', [$startDate, $endDate])
            ->latest()
            ->take(5)
            ->get();

        return view('admin.customers.show', compact(
            'customer',
            'periodInfo',
            'customerLevel',
            'totalOrders',
            'totalSpent',
            'averageOrder',
            'largestOrder',
            'lastOrderDate',
            'mostPurchasedProduct',
            'favoriteCategory',
            'ratingCount',
            'reportCount',
            'recentOrders'
        ));
    }

    /**
     * Helper to parse period date range from Request.
     */
    private function getPeriodDateRange(Request $request): array
    {
        $period = $request->input('period', 'day');
        $now = Carbon::now();

        if ($period === 'month') {
            $month = (int) $request->input('month', $now->month);
            $year = (int) $request->input('year', $now->year);
            $startDate = Carbon::createFromDate($year, $month, 1)->startOfMonth();
            $endDate = Carbon::createFromDate($year, $month, 1)->endOfMonth();
            $label = $startDate->format('F Y');
        } elseif ($period === 'year') {
            $year = (int) $request->input('year', $now->year);
            $startDate = Carbon::createFromDate($year, 1, 1)->startOfYear();
            $endDate = Carbon::createFromDate($year, 12, 31)->endOfYear();
            $label = 'Tahun ' . $year;
        } else {
            // Default: day (Hari Ini)
            $period = 'day';
            $dateStr = $request->input('date', $now->format('Y-m-d'));
            try {
                $date = Carbon::parse($dateStr);
            } catch (\Exception $e) {
                $date = $now;
            }
            $startDate = $date->copy()->startOfDay();
            $endDate = $date->copy()->endOfDay();
            $label = $date->format('d M Y');
        }

        return [
            'period' => $period,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'label' => $label,
            'date' => $request->input('date', $now->format('Y-m-d')),
            'month' => (int) $request->input('month', $now->month),
            'year' => (int) $request->input('year', $now->year),
        ];
    }
}
