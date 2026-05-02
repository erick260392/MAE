<?php

namespace App\Services;

use App\Models\Category;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Quote;
use Illuminate\Support\Collection;

class DashboardStatsService
{
    /**
     * Get overall statistics
     */
    public function getOverallStats(): array
    {
        return [
            'totalProducts' => Product::count(),
            'totalCustomers' => Customer::count(),
            'pendingQuotes' => Quote::where('status', 'pendiente')->count(),
            'totalQuotes' => Quote::count(),
            'totalRevenue' => Quote::where('status', 'confirmada')->sum('total'),
        ];
    }

    /**
     * Get recent quotes with full details
     */
    public function getRecentQuotes(int $limit = 5): Collection
    {
        return Quote::with('customer')
            ->latest()
            ->take($limit)
            ->get();
    }

    /**
     * Get quotes by status for chart
     */
    public function getQuotesByStatus(): array
    {
        $statuses = ['pendiente', 'confirmada', 'cancelada'];
        $data = [];

        foreach ($statuses as $status) {
            $data[$status] = Quote::where('status', $status)->count();
        }

        return $data;
    }

    /**
     * Get monthly quotes data for chart (last 6 months)
     */
    public function getMonthlyQuotesData(): array
    {
        $months = [];
        $values = [];

        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $months[] = $date->format('M');

            $count = Quote::whereMonth('created_at', $date->month)
                ->whereYear('created_at', $date->year)
                ->count();

            $values[] = $count;
        }

        return [
            'labels' => $months,
            'values' => $values,
        ];
    }

    /**
     * Get revenue data by status
     */
    public function getRevenueByStatus(): array
    {
        $statuses = ['pendiente', 'confirmada', 'cancelada'];
        $data = [];

        foreach ($statuses as $status) {
            $data[$status] = Quote::where('status', $status)->sum('total');
        }

        return $data;
    }

    /**
     * Get top customers by quote count
     */
    public function getTopCustomers(int $limit = 5): Collection
    {
        return Customer::withCount('quotes')
            ->orderByDesc('quotes_count')
            ->take($limit)
            ->get(['id', 'name', 'company']);
    }

    /**
     * Get products with low stock
     */
    public function getLowStockProducts(int $threshold = 5): Collection
    {
        return Product::where('stock', '<=', $threshold)
            ->where('active', true)
            ->orderBy('stock')
            ->take(8)
            ->get(['id', 'name', 'stock', 'unit']);
    }

    /**
     * Get top products by quotes
     */
    public function getTopProducts(int $limit = 5): Collection
    {
        return Product::withCount('quoteItems')
            ->orderByDesc('quote_items_count')
            ->take($limit)
            ->get(['id', 'name', 'price']);
    }

    /**
     * Get active categories count
     */
    public function getActiveCategoriesCount(): int
    {
        return Category::has('products')->count();
    }

    /**
     * Get average quote value
     */
    public function getAverageQuoteValue(): float
    {
        return (float) Quote::where('status', '!=', 'cancelada')
            ->avg('total') ?? 0;
    }

    /**
     * Get quote conversion rate
     */
    public function getConversionRate(): float
    {
        $total = Quote::count();

        if ($total === 0) {
            return 0;
        }

        $confirmed = Quote::where('status', 'confirmada')->count();

        return round(($confirmed / $total) * 100, 1);
    }
}
