<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class SalesReportController extends Controller
{
    /**
     * Display the owner sales report with daily, weekly, and monthly filtering.
     */
    public function index(Request $request): View
    {
        $reportData = $this->getSalesReportData($request);

        return view('owner.sales.index', $reportData);
    }

    /**
     * Download structured PDF report of sales based on selected period.
     */
    public function exportPdf(Request $request)
    {
        $reportData = $this->getSalesReportData($request, false);

        $pdf = Pdf::loadView('owner.sales.pdf', $reportData);
        $pdf->setPaper('a4', 'portrait');

        $safePeriod = strtolower($reportData['period']);
        $safeDate = str_replace([' ', '/', '\\', ':', ','], '-', $reportData['periodLabel']);
        $filename = 'Laporan_Penjualan_Tokobii_' . ucfirst($safePeriod) . '_' . date('Ymd_His') . '.pdf';

        return $pdf->download($filename);
    }

    /**
     * Helper method to compute sales report metrics and data.
     */
    private function getSalesReportData(Request $request, bool $paginate = true): array
    {
        $period = $request->input('period', 'daily');
        if (!in_array($period, ['daily', 'weekly', 'monthly'])) {
            $period = 'daily';
        }

        $currentDate = now();
        $dateInput = $request->input('date', $currentDate->toDateString());
        $weekDateInput = $request->input('week_date', $currentDate->toDateString());
        $monthInput = $request->input('month', $currentDate->format('Y-m'));

        switch ($period) {
            case 'weekly':
                $refDate = Carbon::parse($weekDateInput);
                $startDate = $refDate->copy()->startOfWeek();
                $endDate = $refDate->copy()->endOfWeek();
                $periodTitle = 'Laporan Penjualan Mingguan';
                $periodLabel = 'Minggu: ' . $startDate->translatedFormat('d M Y') . ' s/d ' . $endDate->translatedFormat('d M Y');
                break;

            case 'monthly':
                $refMonth = Carbon::parse($monthInput . '-01');
                $startDate = $refMonth->copy()->startOfMonth();
                $endDate = $refMonth->copy()->endOfMonth();
                $periodTitle = 'Laporan Penjualan Bulanan';
                $periodLabel = 'Bulan: ' . $refMonth->translatedFormat('F Y');
                break;

            case 'daily':
            default:
                $refDay = Carbon::parse($dateInput);
                $startDate = $refDay->copy()->startOfDay();
                $endDate = $refDay->copy()->endOfDay();
                $periodTitle = 'Laporan Penjualan Harian';
                $periodLabel = 'Hari/Tanggal: ' . $refDay->translatedFormat('l, d F Y');
                break;
        }

        // Filter orders that are paid or completed
        $baseOrdersQuery = Order::with(['user', 'items.product.category', 'payment'])
            ->where(function ($q) {
                $q->where('order_status', 'completed')
                  ->orWhere('payment_status', 'paid');
            })
            ->whereBetween('created_at', [$startDate, $endDate]);

        // Key summary metrics
        $totalRevenue = (float) ((clone $baseOrdersQuery)->sum('grand_total') ?? 0);
        $totalOrders = (int) (clone $baseOrdersQuery)->count();
        
        $orderIds = (clone $baseOrdersQuery)->pluck('id');
        $totalItemsSold = (int) (OrderItem::whereIn('order_id', $orderIds)->sum('qty') ?? 0);

        $averageOrderValue = $totalOrders > 0 ? round($totalRevenue / $totalOrders) : 0;

        // Payment method breakdown
        $qrisRevenue = (float) ((clone $baseOrdersQuery)->where('payment_method', 'qris')->sum('grand_total') ?? 0);
        $qrisCount = (int) (clone $baseOrdersQuery)->where('payment_method', 'qris')->count();

        $cashRevenue = (float) ((clone $baseOrdersQuery)->where('payment_method', 'cash')->sum('grand_total') ?? 0);
        $cashCount = (int) (clone $baseOrdersQuery)->where('payment_method', 'cash')->count();

        // Top selling products in this period
        $topProducts = OrderItem::whereIn('order_id', $orderIds)
            ->select('product_name', DB::raw('SUM(qty) as total_qty'), DB::raw('SUM(subtotal) as total_amount'))
            ->groupBy('product_name')
            ->orderByDesc('total_qty')
            ->take(5)
            ->get();

        // Orders list
        if ($paginate) {
            $orders = (clone $baseOrdersQuery)->latest()->paginate(15)->withQueryString();
        } else {
            $orders = (clone $baseOrdersQuery)->latest()->get();
        }

        return [
            'period' => $period,
            'periodTitle' => $periodTitle,
            'periodLabel' => $periodLabel,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'dateInput' => $dateInput,
            'weekDateInput' => $weekDateInput,
            'monthInput' => $monthInput,
            'totalRevenue' => $totalRevenue,
            'totalOrders' => $totalOrders,
            'totalItemsSold' => $totalItemsSold,
            'averageOrderValue' => $averageOrderValue,
            'qrisRevenue' => $qrisRevenue,
            'qrisCount' => $qrisCount,
            'cashRevenue' => $cashRevenue,
            'cashCount' => $cashCount,
            'topProducts' => $topProducts,
            'orders' => $orders,
        ];
    }
}
