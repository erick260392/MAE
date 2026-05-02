<?php

use App\Services\DashboardStatsService;
use App\Models\Quote;
use App\Models\Product;
use App\Models\Customer;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->service = new DashboardStatsService();
});

describe('Overall Stats', function () {
    it('returns correct overall statistics', function () {
        $category = Category::factory()->create();
        Product::factory(5)->create(['category_id' => $category->id]);
        Customer::factory(3)->create();
        Quote::factory(2)->create(['status' => 'pendiente']);
        Quote::factory(1)->create(['status' => 'confirmada', 'total' => 1000]);

        $stats = $this->service->getOverallStats();

        expect($stats)
            ->toHaveKeys(['totalProducts', 'totalCustomers', 'pendingQuotes', 'totalQuotes', 'totalRevenue'])
            ->and($stats['totalProducts'])->toBe(5)
            ->and($stats['totalCustomers'])->toBe(3)
            ->and($stats['pendingQuotes'])->toBe(2)
            ->and($stats['totalQuotes'])->toBe(3)
            ->and($stats['totalRevenue'])->toBe(1000.0);
    });
});

describe('Quotes by Status', function () {
    it('returns correct count for each status', function () {
        Quote::factory(2)->create(['status' => 'pendiente']);
        Quote::factory(3)->create(['status' => 'confirmada']);
        Quote::factory(1)->create(['status' => 'cancelada']);

        $result = $this->service->getQuotesByStatus();

        expect($result)
            ->toHaveKeys(['pendiente', 'confirmada', 'cancelada'])
            ->and($result['pendiente'])->toBe(2)
            ->and($result['confirmada'])->toBe(3)
            ->and($result['cancelada'])->toBe(1);
    });
});

describe('Monthly Data', function () {
    it('returns monthly quotes data for 6 months', function () {
        Quote::factory(2)->create(['created_at' => now()]);
        Quote::factory(1)->create(['created_at' => now()->subMonths(1)]);

        $result = $this->service->getMonthlyQuotesData();

        expect($result)
            ->toHaveKeys(['labels', 'values'])
            ->and($result['labels'])->toHaveCount(6)
            ->and($result['values'])->toHaveCount(6);
    });
});

describe('Revenue by Status', function () {
    it('calculates revenue for each status', function () {
        Quote::factory(2)->create(['status' => 'pendiente', 'total' => 100]);
        Quote::factory(1)->create(['status' => 'confirmada', 'total' => 500]);
        Quote::factory(1)->create(['status' => 'cancelada', 'total' => 200]);

        $result = $this->service->getRevenueByStatus();

        expect($result)
            ->and($result['pendiente'])->toBe(200.0)
            ->and($result['confirmada'])->toBe(500.0)
            ->and($result['cancelada'])->toBe(200.0);
    });
});

describe('Top Customers', function () {
    it('returns top customers by quote count', function () {
        $customer1 = Customer::factory()->create();
        $customer2 = Customer::factory()->create();
        $customer3 = Customer::factory()->create();

        Quote::factory(5)->create(['customer_id' => $customer1->id]);
        Quote::factory(3)->create(['customer_id' => $customer2->id]);
        Quote::factory(1)->create(['customer_id' => $customer3->id]);

        $result = $this->service->getTopCustomers(2);

        expect($result)
            ->toHaveCount(2)
            ->and($result->first()->id)->toBe($customer1->id)
            ->and($result->first()->quotes_count)->toBe(5);
    });
});

describe('Low Stock Products', function () {
    it('returns products with low stock', function () {
        $category = Category::factory()->create();
        Product::factory()->create(['stock' => 3, 'category_id' => $category->id, 'active' => true]);
        Product::factory()->create(['stock' => 10, 'category_id' => $category->id, 'active' => true]);

        $result = $this->service->getLowStockProducts(5);

        expect($result)
            ->toHaveCount(1)
            ->and($result->first()->stock)->toBe(3);
    });

    it('ignores inactive products', function () {
        $category = Category::factory()->create();
        Product::factory()->create(['stock' => 2, 'category_id' => $category->id, 'active' => false]);
        Product::factory()->create(['stock' => 3, 'category_id' => $category->id, 'active' => true]);

        $result = $this->service->getLowStockProducts(5);

        expect($result)->toHaveCount(1);
    });
});

describe('Top Products', function () {
    it('returns top products by quote count', function () {
        $category = Category::factory()->create();
        $product1 = Product::factory()->create(['category_id' => $category->id]);
        $product2 = Product::factory()->create(['category_id' => $category->id]);

        Quote::factory(3)->has(
            \App\Models\QuoteItem::factory(2)->for($product1, 'product')
        )->create();

        Quote::factory(1)->has(
            \App\Models\QuoteItem::factory(1)->for($product2, 'product')
        )->create();

        $result = $this->service->getTopProducts(2);

        expect($result)->toHaveCount(2);
    });
});

describe('Metrics', function () {
    it('calculates average quote value correctly', function () {
        Quote::factory()->create(['total' => 100, 'status' => 'confirmada']);
        Quote::factory()->create(['total' => 200, 'status' => 'confirmada']);
        Quote::factory()->create(['total' => 300, 'status' => 'pendiente']);

        $avg = $this->service->getAverageQuoteValue();

        expect($avg)->toBe(200.0);
    });

    it('calculates conversion rate', function () {
        Quote::factory(2)->create(['status' => 'pendiente']);
        Quote::factory(1)->create(['status' => 'confirmada']);

        $rate = $this->service->getConversionRate();

        expect($rate)->toBe(33.3);
    });

    it('returns zero conversion rate when no quotes', function () {
        $rate = $this->service->getConversionRate();

        expect($rate)->toBe(0);
    });

    it('counts active categories', function () {
        $category = Category::factory()->create();
        Product::factory()->create(['category_id' => $category->id]);

        // Create category without products
        Category::factory()->create();

        $count = $this->service->getActiveCategoriesCount();

        expect($count)->toBe(1);
    });
});
