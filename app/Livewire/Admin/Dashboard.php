<?php

namespace App\Livewire\Admin;

use App\Services\DashboardStatsService;
use Livewire\Component;

class Dashboard extends Component
{
    public function __construct(private DashboardStatsService $stats) {}

    public function render()
    {
        return view('livewire.admin.dashboard', [
            // Overall metrics
            'stats' => $this->stats->getOverallStats(),
            'conversionRate' => $this->stats->getConversionRate(),
            'averageQuoteValue' => $this->stats->getAverageQuoteValue(),

            // Chart data
            'quotesByStatus' => $this->stats->getQuotesByStatus(),
            'monthlyQuotes' => $this->stats->getMonthlyQuotesData(),
            'revenueByStatus' => $this->stats->getRevenueByStatus(),

            // Lists
            'recentQuotes' => $this->stats->getRecentQuotes(5),
            'topCustomers' => $this->stats->getTopCustomers(5),
            'lowStockProducts' => $this->stats->getLowStockProducts(5),
            'topProducts' => $this->stats->getTopProducts(5),

            // Additional metrics
            'activeCategories' => $this->stats->getActiveCategoriesCount(),
        ])->layout('layouts.admin', ['title' => 'Dashboard']);
    }
}
