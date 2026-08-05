<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
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

        // Period-filtered Customer Analytics Statistics
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

        // Placeholders for Order & Revenue analytics (until Order system tables exist)
        $totalOrders = 0;
        $totalRevenue = 0;
        $totalProductsPurchased = 0;
        $totalRatings = 0;

        // Query Builder for Customer List
        $query = User::where('role', 'customer');

        // Apply period filter to table list if period is explicitly selected
        if ($request->filled('period')) {
            $query->whereBetween('created_at', [$startDate, $endDate]);
        }

        // Check if phone column exists dynamically
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
     * Display the specified customer detail with period analytics.
     */
    public function show(User $customer, Request $request): View
    {
        // Security check: Ensure the requested user is strictly a customer
        if (!$customer->isCustomer()) {
            abort(404);
        }

        // Parse period filter for customer detail analytics
        $periodInfo = $this->getPeriodDateRange($request);

        // Customer Level tier (Default: New Customer)
        $customerLevel = 'New Customer';

        // Purchase Summary metrics (placeholders until Order system is built)
        $totalOrders = 0;
        $totalSpent = 0;
        $averageOrder = 0;
        $largestOrder = 0;
        $lastOrderDate = null;

        // Product Summary metrics
        $mostPurchasedProduct = null;
        $favoriteCategory = null;
        $favoriteBrand = null;

        // Activity Summary metrics
        $loginCount = 0;
        $checkoutCount = 0;
        $ratingCount = 0;
        $favoriteCount = 0;
        $cartCount = 0;

        // Placeholder collections
        $recentOrders = collect();
        $activities = collect();

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
            'favoriteBrand',
            'loginCount',
            'checkoutCount',
            'ratingCount',
            'favoriteCount',
            'cartCount',
            'recentOrders',
            'activities'
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
