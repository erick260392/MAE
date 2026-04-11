<?php

use App\Models\Category;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Quote;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

// --- Catálogo público ---

test('catálogo público carga correctamente', function () {
    Category::create(['name' => 'Mangueras', 'slug' => 'mangueras']);
    $this->get('/')->assertStatus(200);
});

test('redirige a login si no está autenticado', function () {
    $this->get('/admin/dashboard')->assertRedirect('/admin/login');
});

test('login muestra formulario', function () {
    $this->get('/admin/login')->assertStatus(200);
});

// --- Autenticación ---

test('admin puede iniciar sesión', function () {
    $user = User::factory()->create(['email' => 'admin@mae.mx', 'password' => bcrypt('admin123')]);
    $this->post('/admin/logout');
    $this->actingAs($user)->get('/admin/dashboard')->assertStatus(200);
});

test('credenciales incorrectas no permiten acceso', function () {
    User::factory()->create(['email' => 'admin@mae.mx', 'password' => bcrypt('admin123')]);
    $this->post('/admin/login', ['email' => 'admin@mae.mx', 'password' => 'wrongpass']);
    $this->assertGuest();
});

// --- Panel admin ---

test('dashboard carga con usuario autenticado', function () {
    $user = User::factory()->create();
    $this->actingAs($user)->get('/admin/dashboard')->assertStatus(200);
});

test('categorías carga con usuario autenticado', function () {
    $user = User::factory()->create();
    $this->actingAs($user)->get('/admin/categories')->assertStatus(200);
});

test('productos carga con usuario autenticado', function () {
    $user = User::factory()->create();
    $this->actingAs($user)->get('/admin/products')->assertStatus(200);
});

test('clientes carga con usuario autenticado', function () {
    $user = User::factory()->create();
    $this->actingAs($user)->get('/admin/customers')->assertStatus(200);
});

test('cotizaciones carga con usuario autenticado', function () {
    $user = User::factory()->create();
    $this->actingAs($user)->get('/admin/quotes')->assertStatus(200);
});

test('inventario carga con usuario autenticado', function () {
    $user = User::factory()->create();
    $this->actingAs($user)->get('/admin/inventory')->assertStatus(200);
});

test('nueva cotización carga con usuario autenticado', function () {
    $user = User::factory()->create();
    $this->actingAs($user)->get('/admin/quotes/create')->assertStatus(200);
});

// --- Modelos ---

test('se puede crear una categoría', function () {
    Category::create(['name' => 'Mangueras', 'slug' => 'mangueras']);
    expect(Category::count())->toBe(1);
});

test('se puede crear un producto', function () {
    $category = Category::create(['name' => 'Mangueras', 'slug' => 'mangueras']);
    Product::create([
        'category_id' => $category->id,
        'name'        => 'Manguera 1/4"',
        'slug'        => 'manguera-1-4',
        'price'       => 85.00,
        'stock'       => 100,
        'unit'        => 'metro',
    ]);
    expect(Product::count())->toBe(1);
});

test('se puede crear un cliente', function () {
    Customer::create(['name' => 'Juan Pérez', 'phone' => '5512345678']);
    expect(Customer::count())->toBe(1);
});

test('cotización genera folio automático', function () {
    $customer = Customer::create(['name' => 'Juan Pérez', 'phone' => '5512345678']);
    $quote = Quote::create(['customer_id' => $customer->id, 'total' => 500]);
    expect($quote->folio)->toBe('MAE-0001');
});

test('segundo folio se incrementa correctamente', function () {
    $customer = Customer::create(['name' => 'Juan Pérez', 'phone' => '5512345678']);
    Quote::create(['customer_id' => $customer->id, 'total' => 100]);
    $quote2 = Quote::create(['customer_id' => $customer->id, 'total' => 200]);
    expect($quote2->folio)->toBe('MAE-0002');
});

// --- PDF ---

test('PDF de cotización se genera correctamente', function () {
    $user = User::factory()->create();
    $customer = Customer::create(['name' => 'Juan Pérez', 'phone' => '5512345678']);
    $quote = Quote::create(['customer_id' => $customer->id, 'total' => 500]);

    $this->actingAs($user)
        ->get("/admin/quotes/{$quote->id}/pdf")
        ->assertStatus(200)
        ->assertHeader('content-type', 'application/pdf');
});
