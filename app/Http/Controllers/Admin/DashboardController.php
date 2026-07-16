<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\Product;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Display the admin dashboard with metrics.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $products = Product::orderBy('created_at', 'desc')->get();

        // Get today's sales
        $todaySales = Sale::completed()->today()->get();

        // Calculate metrics
        $metrics = $this->calculateMetrics($todaySales);

        // Get recent transactions
        $recentTransactions = Sale::completed()
            ->orderBy('sale_date', 'desc')
            ->limit(5)
            ->get();

        // Get top products
        $topProducts = $this->getTopProducts();

        // Get sales chart data (last 7 days)
        $salesChartData = $this->getSalesChartData();

        // Get category breakdown
        $categoryBreakdown = $this->getCategoryBreakdown();

        // Get cashier login time from cache (shared across sessions)
        // Only show if cashier session is actually active
        $cashierSessionActive = \Cache::get('cashier_session_active', false);
        $cashierLoginTime = null;
        
        if ($cashierSessionActive) {
            $cashierLoginTime = \Cache::get('cashier_login_time');
        }

        return view('admin.dashboard', compact(
            'products',
            'metrics',
            'recentTransactions',
            'topProducts',
            'salesChartData',
            'categoryBreakdown',
            'cashierLoginTime'
        ));
    }

    /**
     * Calculate dashboard metrics from sales.
     *
     * @param \Illuminate\Database\Eloquent\Collection $sales
     * @return array
     */
    private function calculateMetrics($sales)
    {
        $totalRevenue = $sales->sum('total');
        $transactionCount = $sales->count();
        
        // Calculate profit (total - cost of goods sold)
        $profit = 0;
        foreach ($sales as $sale) {
            foreach ($sale->items as $item) {
                $profit += ($item->unit_price - $item->purchase_price) * $item->quantity;
            }
        }

        // Calculate average cart value
        $averageCart = $transactionCount > 0 ? $totalRevenue / $transactionCount : 0;

        return [
            'revenue' => $totalRevenue,
            'profit' => $profit,
            'transactions' => $transactionCount,
            'average_cart' => $averageCart,
        ];
    }

    /**
     * Get top selling products.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    private function getTopProducts()
    {
        return SaleItem::select('product_id', 'product_name', 'product_reference')
            ->selectRaw('SUM(quantity) as total_quantity')
            ->selectRaw('SUM(subtotal) as total_revenue')
            ->with('product:id,image')
            ->groupBy('product_id', 'product_name', 'product_reference')
            ->orderBy('total_quantity', 'desc')
            ->limit(4)
            ->get();
    }

    /**
     * Get sales chart data for today (hourly real-time data).
     *
     * @return array
     */
    private function getSalesChartData()
    {
        $data = [];
        $labels = [];

        // Get hourly data for today (from 0:00 AM to current hour)
        $currentHour = now()->hour;
        $startHour = 0; // Start from midnight

        for ($hour = $startHour; $hour <= $currentHour; $hour++) {
            $labels[] = $hour . 'h';

            $hourStart = now()->setHour($hour)->setMinute(0)->setSecond(0);
            $hourEnd = now()->setHour($hour + 1)->setMinute(0)->setSecond(0);

            $hourlySales = Sale::completed()
                ->whereDate('sale_date', now())
                ->where('sale_date', '>=', $hourStart)
                ->where('sale_date', '<', $hourEnd)
                ->sum('total');

            $data[] = (float) $hourlySales;
        }

        \Log::info('Sales chart data', ['labels' => $labels, 'data' => $data, 'current_hour' => $currentHour]);

        return [
            'labels' => $labels,
            'data' => $data,
        ];
    }

    /**
     * Get category breakdown for sales.
     *
     * @return array
     */
    private function getCategoryBreakdown()
    {
        $categories = [
            'alimentation' => 0,
            'boissons' => 0,
            'hygiene' => 0,
            'divers' => 0,
        ];

        $todaySales = Sale::completed()->today()->get();
        
        foreach ($todaySales as $sale) {
            foreach ($sale->items as $item) {
                if ($item->product && isset($categories[$item->product->category])) {
                    $categories[$item->product->category] += $item->subtotal;
                }
            }
        }

        $total = array_sum($categories);
        
        if ($total > 0) {
            foreach ($categories as $key => $value) {
                $categories[$key] = [
                    'amount' => $value,
                    'percentage' => round(($value / $total) * 100),
                ];
            }
        }

        return $categories;
    }
}