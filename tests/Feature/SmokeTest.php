<?php

use App\Models\Category;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Quote;
use App\Models\QuoteItem;
use App\Models\User;
use Illuminate\Support\Facades\Route;

test('public routes are accessible', function () {
    $this->get(route('catalog'))->assertSuccessful();
    $this->get(route('admin.login'))->assertSuccessful();
});

test('protected admin pages redirect guests to login', function () {
    foreach (adminRouteUrls() as $routeName => $url) {
        $this->get($url)
            ->assertRedirect(route('admin.login'));
    }
});

test('authenticated users can open admin pages', function () {
    $this->actingAs(User::factory()->create());

    foreach (adminRouteUrls() as $routeName => $url) {
        $response = $this->get($url);

        if ($routeName === 'admin.quotes.pdf') {
            $response->assertSuccessful();
            expect($response->headers->get('content-type'))->toContain('application/pdf');

            continue;
        }

        $response->assertSuccessful();
    }
});

function adminRouteUrls(): array
{
    $customer = Customer::query()->create([
        'name' => 'Cliente Smoke',
        'phone' => '5551234567',
    ]);

    $category = Category::query()->create([
        'name' => 'Categoria Smoke',
        'description' => 'Categoria creada para smoke tests.',
    ]);

    $product = Product::query()->create([
        'category_id' => $category->id,
        'sku' => 'SMOKE-001',
        'name' => 'Producto Smoke',
        'description' => 'Producto para smoke tests.',
        'price' => 100,
        'stock' => 5,
        'unit' => 'pieza',
        'active' => true,
    ]);

    $quote = Quote::query()->create([
        'customer_id' => $customer->id,
        'status' => 'pendiente',
        'total' => 116,
        'conditions' => 'Contado',
    ]);

    QuoteItem::query()->create([
        'quote_id' => $quote->id,
        'product_id' => $product->id,
        'quantity' => 1,
        'unit_price' => 100,
        'subtotal' => 100,
    ]);

    return collect(Route::getRoutes())
        ->filter(fn ($route) => str_starts_with($route->getName() ?? '', 'admin.'))
        ->filter(fn ($route) => in_array('GET', $route->methods(), true) || in_array('HEAD', $route->methods(), true))
        ->reject(fn ($route) => $route->getName() === 'admin.login')
        ->mapWithKeys(fn ($route) => [
            $route->getName() => route($route->getName(), ['quote' => $quote]),
        ])
        ->all();
}
