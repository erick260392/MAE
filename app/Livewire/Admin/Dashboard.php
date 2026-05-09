<?php

namespace App\Livewire\Admin;

use App\Services\DashboardStatsService;
use Livewire\Component;

class Dashboard extends Component
{
    public function render(DashboardStatsService $stats)
    {
        return view('livewire.admin.dashboard', [
            'stats' => $stats->getOverallStats(),
            'conversionRate' => $stats->getConversionRate(),
            'averageQuoteValue' => $stats->getAverageQuoteValue(),
            'quotesByStatus' => $stats->getQuotesByStatus(),
            'monthlyQuotes' => $stats->getMonthlyQuotesData(),
            'revenueByStatus' => $stats->getRevenueByStatus(),
            'recentQuotes' => $stats->getRecentQuotes(5),
            'topCustomers' => $stats->getTopCustomers(5),
            'lowStockProducts' => $stats->getLowStockProducts(5),
            'topProducts' => $stats->getTopProducts(5),
            'activeCategories' => $stats->getActiveCategoriesCount(),
        ])->layout('layouts.admin', ['title' => 'Dashboard']);
    }
}
