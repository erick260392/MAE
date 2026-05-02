<?php

use App\Livewire\Admin\Dashboard;
use App\Models\Quote;
use App\Models\Product;
use App\Models\Customer;
use App\Models\Category;
use App\Models\User;
use Livewire\Livewire;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->user = User::factory()->create();
});

it('renders the dashboard component successfully', function () {
    Livewire::actingAs($this->user)
        ->test(Dashboard::class)
        ->assertStatus(200)
        ->assertViewHas('stats')
        ->assertViewHas('recentQuotes')
        ->assertViewHas('quotesByStatus')
        ->assertViewHas('monthlyQuotes');
});

it('displays correct stats', function () {
    $category = Category::factory()->create();
    Product::factory(5)->create(['category_id' => $category->id]);
    Customer::factory(3)->create();
    Quote::factory(2)->create(['status' => 'pendiente']);
    Quote::factory(1)->create(['status' => 'confirmada']);

    Livewire::actingAs($this->user)
        ->test(Dashboard::class)
        ->assertViewHas('stats', function ($stats) {
            return $stats['totalProducts'] === 5
                && $stats['totalCustomers'] === 3
                && $stats['pendingQuotes'] === 2
                && $stats['totalQuotes'] === 3;
        });
});

it('calculates conversion rate correctly', function () {
    Quote::factory(2)->create(['status' => 'pendiente']);
    Quote::factory(1)->create(['status' => 'confirmada']);

    Livewire::actingAs($this->user)
        ->test(Dashboard::class)
        ->assertViewHas('conversionRate', function ($rate) {
            return $rate == 33.3;
        });
});

it('calculates average quote value', function () {
    Quote::factory()->create(['total' => 100, 'status' => 'confirmada']);
    Quote::factory()->create(['total' => 200, 'status' => 'confirmada']);

    Livewire::actingAs($this->user)
        ->test(Dashboard::class)
        ->assertViewHas('averageQuoteValue', function ($avg) {
            return $avg == 150;
        });
});

it('groups quotes by status', function () {
    Quote::factory(2)->create(['status' => 'pendiente']);
    Quote::factory(3)->create(['status' => 'confirmada']);
    Quote::factory(1)->create(['status' => 'cancelada']);

    Livewire::actingAs($this->user)
        ->test(Dashboard::class)
        ->assertViewHas('quotesByStatus', function ($data) {
            return $data['pendiente'] === 2
                && $data['confirmada'] === 3
                && $data['cancelada'] === 1;
        });
});

it('includes recent quotes with customer relationship', function () {
    $customers = Customer::factory(2)->create();

    Quote::factory()->create([
        'customer_id' => $customers[0]->id,
        'created_at' => now(),
    ]);
    Quote::factory()->create([
        'customer_id' => $customers[1]->id,
        'created_at' => now()->subDay(),
    ]);

    Livewire::actingAs($this->user)
        ->test(Dashboard::class)
        ->assertViewHas('recentQuotes', function ($quotes) {
            return count($quotes) === 2
                && $quotes->first()->customer !== null;
        });
});

it('identifies low stock products', function () {
    $category = Category::factory()->create();
    Product::factory()->create(['stock' => 3, 'category_id' => $category->id, 'active' => true]);
    Product::factory()->create(['stock' => 10, 'category_id' => $category->id, 'active' => true]);

    Livewire::actingAs($this->user)
        ->test(Dashboard::class)
        ->assertViewHas('lowStockProducts', function ($products) {
            return $products->count() === 1
                && $products->first()->stock === 3;
        });
});

it('returns top customers by quote count', function () {
    $customer1 = Customer::factory()->create();
    $customer2 = Customer::factory()->create();
    $customer3 = Customer::factory()->create();

    Quote::factory(5)->create(['customer_id' => $customer1->id]);
    Quote::factory(3)->create(['customer_id' => $customer2->id]);
    Quote::factory(1)->create(['customer_id' => $customer3->id]);

    Livewire::actingAs($this->user)
        ->test(Dashboard::class)
        ->assertViewHas('topCustomers', function ($customers) {
            $customerIds = $customers->pluck('id')->toArray();
            return $customerIds[0] == $customers->sortByDesc('quotes_count')->first()->id;
        });
});

it('returns monthly quotes data for chart', function () {
    Quote::factory(2)->create(['created_at' => now()]);
    Quote::factory(1)->create(['created_at' => now()->subMonths(1)]);

    Livewire::actingAs($this->user)
        ->test(Dashboard::class)
        ->assertViewHas('monthlyQuotes', function ($data) {
            return isset($data['labels']) && isset($data['values'])
                && count($data['labels']) === 6
                && count($data['values']) === 6;
        });
});

it('counts active categories', function () {
    $category = Category::factory()->create();
    Product::factory(3)->create(['category_id' => $category->id]);

    // Create a category with no products
    Category::factory()->create();

    Livewire::actingAs($this->user)
        ->test(Dashboard::class)
        ->assertViewHas('activeCategories', function ($count) {
            return $count === 1;
        });
});

it('requires authentication', function () {
    $this->get(route('admin.dashboard'))
        ->assertRedirectToRoute('admin.login');
});
