<?php

use App\Livewire\Admin\Categories;
use App\Livewire\Admin\Customers;
use App\Livewire\Admin\Inventory;
use App\Livewire\Admin\Login;
use App\Livewire\Admin\QuoteCreate;
use App\Livewire\Admin\Quotes;
use App\Livewire\Catalog\ProductCatalog;
use App\Livewire\Catalog\QuoteCart;
use App\Models\Category;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Quote;
use App\Models\QuoteItem;
use App\Models\StockMovement;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

// ============================================================
// RUTAS Y ACCESO
// ============================================================

describe('acceso público', function () {
    test('catálogo carga correctamente', function () {
        Category::create(['name' => 'Mangueras', 'slug' => 'mangueras']);
        $this->get('/')->assertStatus(200);
    });

    test('redirige a login si no autenticado', function () {
        $this->get('/admin/dashboard')->assertRedirect('/admin/login');
        $this->get('/admin/products')->assertRedirect('/admin/login');
        $this->get('/admin/customers')->assertRedirect('/admin/login');
        $this->get('/admin/quotes')->assertRedirect('/admin/login');
        $this->get('/admin/inventory')->assertRedirect('/admin/login');
    });

    test('login muestra formulario', function () {
        $this->get('/admin/login')->assertStatus(200);
    });
});

describe('autenticación', function () {
    test('admin puede iniciar sesión con credenciales correctas', function () {
        $user = User::factory()->create(['email' => 'admin@mae.mx', 'password' => bcrypt('admin123')]);
        $this->actingAs($user)->get('/admin/dashboard')->assertStatus(200);
    });

    test('credenciales incorrectas no permiten acceso', function () {
        User::factory()->create(['email' => 'admin@mae.mx', 'password' => bcrypt('admin123')]);
        Livewire::test(Login::class)
            ->set('email', 'admin@mae.mx')
            ->set('password', 'wrongpass')
            ->call('login')
            ->assertHasErrors('email');
    });

    test('campos vacíos en login muestran errores', function () {
        Livewire::test(Login::class)
            ->call('login')
            ->assertHasErrors(['email', 'password']);
    });

    test('usuario autenticado accede a todas las rutas admin', function () {
        $user = User::factory()->create();
        $this->actingAs($user);

        foreach (['/admin/dashboard', '/admin/products', '/admin/categories', '/admin/customers', '/admin/quotes', '/admin/inventory', '/admin/quotes/create'] as $route) {
            $this->get($route)->assertStatus(200);
        }
    });
});

// ============================================================
// MODELOS Y RELACIONES
// ============================================================

describe('modelo Category', function () {
    test('se puede crear una categoría', function () {
        Category::create(['name' => 'Mangueras', 'slug' => 'mangueras']);
        expect(Category::count())->toBe(1);
    });

    test('categoría tiene relación hasMany con productos', function () {
        $cat = Category::create(['name' => 'Mangueras', 'slug' => 'mangueras']);
        Product::create(['category_id' => $cat->id, 'name' => 'P1', 'slug' => 'p1', 'price' => 10, 'stock' => 5, 'unit' => 'pieza']);
        expect($cat->products()->count())->toBe(1);
    });
});

describe('modelo Product', function () {
    test('se puede crear un producto', function () {
        $cat = Category::create(['name' => 'Mangueras', 'slug' => 'mangueras']);
        Product::create(['category_id' => $cat->id, 'name' => 'Manguera 1/4"', 'slug' => 'manguera-1-4', 'price' => 85.00, 'stock' => 100, 'unit' => 'metro']);
        expect(Product::count())->toBe(1);
    });

    test('producto pertenece a categoría', function () {
        $cat = Category::create(['name' => 'Mangueras', 'slug' => 'mangueras']);
        $product = Product::create(['category_id' => $cat->id, 'name' => 'P1', 'slug' => 'p1', 'price' => 10, 'stock' => 5, 'unit' => 'pieza']);
        expect($product->category->name)->toBe('Mangueras');
    });

    test('producto inactivo no aparece en catálogo', function () {
        $cat = Category::create(['name' => 'Mangueras', 'slug' => 'mangueras']);
        Product::create(['category_id' => $cat->id, 'name' => 'Activo', 'slug' => 'activo', 'price' => 10, 'stock' => 5, 'unit' => 'pieza', 'active' => true]);
        Product::create(['category_id' => $cat->id, 'name' => 'Inactivo', 'slug' => 'inactivo', 'price' => 10, 'stock' => 5, 'unit' => 'pieza', 'active' => false]);

        $visible = Product::where('active', true)->get();
        expect($visible)->toHaveCount(1)
            ->and($visible->first()->name)->toBe('Activo');
    });
});

describe('modelo Customer', function () {
    test('se puede crear un cliente', function () {
        Customer::create(['name' => 'Juan Pérez', 'phone' => '5512345678']);
        expect(Customer::count())->toBe(1);
    });

    test('firstOrCreate no duplica clientes por teléfono', function () {
        Customer::firstOrCreate(['phone' => '5512345678'], ['name' => 'Juan']);
        Customer::firstOrCreate(['phone' => '5512345678'], ['name' => 'Juan Duplicado']);
        expect(Customer::count())->toBe(1);
    });

    test('cliente tiene relación hasMany con cotizaciones', function () {
        $customer = Customer::create(['name' => 'Juan', 'phone' => '5512345678']);
        Quote::create(['customer_id' => $customer->id, 'total' => 100]);
        expect($customer->quotes()->count())->toBe(1);
    });
});

describe('modelo Quote', function () {
    test('cotización genera folio automático MAE-0001', function () {
        $customer = Customer::create(['name' => 'Juan', 'phone' => '5512345678']);
        $quote = Quote::create(['customer_id' => $customer->id, 'total' => 500]);
        expect($quote->folio)->toBe('MAE-0001');
    });

    test('folios se incrementan correctamente', function () {
        $customer = Customer::create(['name' => 'Juan', 'phone' => '5512345678']);
        $q1 = Quote::create(['customer_id' => $customer->id, 'total' => 100]);
        $q2 = Quote::create(['customer_id' => $customer->id, 'total' => 200]);
        $q3 = Quote::create(['customer_id' => $customer->id, 'total' => 300]);
        expect($q1->folio)->toBe('MAE-0001')
            ->and($q2->folio)->toBe('MAE-0002')
            ->and($q3->folio)->toBe('MAE-0003');
    });

    test('cotización tiene status pendiente por defecto', function () {
        $customer = Customer::create(['name' => 'Juan', 'phone' => '5512345678']);
        $quote = Quote::create(['customer_id' => $customer->id, 'total' => 100]);
        expect($quote->fresh()->status)->toBe('pendiente');
    });

    test('cotización pertenece a cliente', function () {
        $customer = Customer::create(['name' => 'Juan', 'phone' => '5512345678']);
        $quote = Quote::create(['customer_id' => $customer->id, 'total' => 100]);
        expect($quote->customer->name)->toBe('Juan');
    });

    test('cotización tiene items', function () {
        $cat = Category::create(['name' => 'Cat', 'slug' => 'cat']);
        $product = Product::create(['category_id' => $cat->id, 'name' => 'P1', 'slug' => 'p1', 'price' => 50, 'stock' => 10, 'unit' => 'pieza']);
        $customer = Customer::create(['name' => 'Juan', 'phone' => '5512345678']);
        $quote = Quote::create(['customer_id' => $customer->id, 'total' => 100]);
        QuoteItem::create(['quote_id' => $quote->id, 'product_id' => $product->id, 'quantity' => 2, 'unit_price' => 50, 'subtotal' => 100]);
        expect($quote->items()->count())->toBe(1);
    });
});

describe('modelo StockMovement', function () {
    test('se puede registrar entrada de stock', function () {
        $cat = Category::create(['name' => 'Cat', 'slug' => 'cat']);
        $product = Product::create(['category_id' => $cat->id, 'name' => 'P1', 'slug' => 'p1', 'price' => 50, 'stock' => 10, 'unit' => 'pieza']);
        StockMovement::create(['product_id' => $product->id, 'type' => 'entrada', 'quantity' => 20, 'reason' => 'Compra']);
        expect(StockMovement::count())->toBe(1);
    });
});

// ============================================================
// LIVEWIRE — CATÁLOGO
// ============================================================

describe('ProductCatalog Livewire', function () {
    test('renderiza correctamente', function () {
        Category::create(['name' => 'Mangueras', 'slug' => 'mangueras']);
        Livewire::test(ProductCatalog::class)->assertStatus(200);
    });

    test('filtra por categoría', function () {
        $cat1 = Category::create(['name' => 'Mangueras', 'slug' => 'mangueras']);
        $cat2 = Category::create(['name' => 'Conexiones', 'slug' => 'conexiones']);
        Product::create(['category_id' => $cat1->id, 'name' => 'Manguera A', 'slug' => 'ma', 'price' => 10, 'stock' => 5, 'unit' => 'metro', 'active' => true]);
        Product::create(['category_id' => $cat2->id, 'name' => 'Conexión B', 'slug' => 'cb', 'price' => 20, 'stock' => 5, 'unit' => 'pieza', 'active' => true]);

        Livewire::test(ProductCatalog::class)
            ->call('setCategory', $cat1->id)
            ->assertSee('Manguera A')
            ->assertDontSee('Conexión B');
    });

    test('búsqueda filtra productos por nombre', function () {
        $cat = Category::create(['name' => 'Cat', 'slug' => 'cat']);
        Product::create(['category_id' => $cat->id, 'name' => 'Manguera Roja', 'slug' => 'mr', 'price' => 10, 'stock' => 5, 'unit' => 'metro', 'active' => true]);
        Product::create(['category_id' => $cat->id, 'name' => 'Conexión Azul', 'slug' => 'ca', 'price' => 20, 'stock' => 5, 'unit' => 'pieza', 'active' => true]);

        Livewire::test(ProductCatalog::class)
            ->set('search', 'Manguera')
            ->assertSee('Manguera Roja')
            ->assertDontSee('Conexión Azul');
    });

    test('setCategory limpia búsqueda', function () {
        $cat = Category::create(['name' => 'Cat', 'slug' => 'cat']);
        Livewire::test(ProductCatalog::class)
            ->set('search', 'algo')
            ->call('setCategory', $cat->id)
            ->assertSet('search', '');
    });

    test('no muestra productos inactivos', function () {
        $cat = Category::create(['name' => 'Cat', 'slug' => 'cat']);
        Product::create(['category_id' => $cat->id, 'name' => 'Visible', 'slug' => 'v', 'price' => 10, 'stock' => 5, 'unit' => 'pieza', 'active' => true]);
        Product::create(['category_id' => $cat->id, 'name' => 'Oculto', 'slug' => 'o', 'price' => 10, 'stock' => 5, 'unit' => 'pieza', 'active' => false]);

        Livewire::test(ProductCatalog::class)
            ->assertSee('Visible')
            ->assertDontSee('Oculto');
    });
});

describe('QuoteCart Livewire', function () {
    test('renderiza correctamente', function () {
        Livewire::test(QuoteCart::class)->assertStatus(200);
    });

    test('agrega producto al carrito', function () {
        $cat = Category::create(['name' => 'Cat', 'slug' => 'cat']);
        $product = Product::create(['category_id' => $cat->id, 'name' => 'P1', 'slug' => 'p1', 'price' => 50, 'stock' => 10, 'unit' => 'pieza', 'active' => true]);

        Livewire::test(QuoteCart::class)
            ->dispatch('cart-add', productId: $product->id)
            ->assertSet('showCart', true);
    });

    test('incrementa cantidad al agregar mismo producto', function () {
        $cat = Category::create(['name' => 'Cat', 'slug' => 'cat']);
        $product = Product::create(['category_id' => $cat->id, 'name' => 'P1', 'slug' => 'p1', 'price' => 50, 'stock' => 10, 'unit' => 'pieza', 'active' => true]);

        $component = Livewire::test(QuoteCart::class)
            ->dispatch('cart-add', productId: $product->id)
            ->dispatch('cart-add', productId: $product->id);

        expect($component->get('items')[$product->id]['quantity'])->toEqual(2);
    });

    test('incrementa y decrementa cantidad', function () {
        $cat = Category::create(['name' => 'Cat', 'slug' => 'cat']);
        $product = Product::create(['category_id' => $cat->id, 'name' => 'P1', 'slug' => 'p1', 'price' => 50, 'stock' => 10, 'unit' => 'pieza', 'active' => true]);

        $component = Livewire::test(QuoteCart::class)
            ->dispatch('cart-add', productId: $product->id)
            ->call('increment', $product->id)
            ->call('increment', $product->id);

        expect($component->get('items')[$product->id]['quantity'])->toEqual(3);

        $component->call('decrement', $product->id);
        expect($component->get('items')[$product->id]['quantity'])->toEqual(2);
    });

    test('elimina producto del carrito', function () {
        $cat = Category::create(['name' => 'Cat', 'slug' => 'cat']);
        $product = Product::create(['category_id' => $cat->id, 'name' => 'P1', 'slug' => 'p1', 'price' => 50, 'stock' => 10, 'unit' => 'pieza', 'active' => true]);

        Livewire::test(QuoteCart::class)
            ->dispatch('cart-add', productId: $product->id)
            ->call('remove', $product->id)
            ->assertSet('items', []);
    });

    test('carrito se cierra al quedar vacío', function () {
        $cat = Category::create(['name' => 'Cat', 'slug' => 'cat']);
        $product = Product::create(['category_id' => $cat->id, 'name' => 'P1', 'slug' => 'p1', 'price' => 50, 'stock' => 10, 'unit' => 'pieza', 'active' => true]);

        Livewire::test(QuoteCart::class)
            ->dispatch('cart-add', productId: $product->id)
            ->call('remove', $product->id)
            ->assertSet('showCart', false);
    });

    test('envío de cotización valida campos requeridos', function () {
        Livewire::test(QuoteCart::class)
            ->call('submitQuote')
            ->assertHasErrors(['name', 'phone']);
    });

    test('envío de cotización crea quote y customer en BD', function () {
        $cat = Category::create(['name' => 'Cat', 'slug' => 'cat']);
        $product = Product::create(['category_id' => $cat->id, 'name' => 'P1', 'slug' => 'p1', 'price' => 50, 'stock' => 10, 'unit' => 'pieza', 'active' => true]);

        Livewire::test(QuoteCart::class)
            ->dispatch('cart-add', productId: $product->id)
            ->set('name', 'Juan Pérez')
            ->set('phone', '5512345678')
            ->call('submitQuote')
            ->assertSet('submitted', true);

        expect(Customer::count())->toBe(1)
            ->and(Quote::count())->toBe(1)
            ->and(QuoteItem::count())->toBe(1);
    });
});

// ============================================================
// LIVEWIRE — ADMIN
// ============================================================

describe('Admin Categories Livewire', function () {
    test('crea categoría correctamente', function () {
        $user = User::factory()->create();
        Livewire::actingAs($user)
            ->test(Categories::class)
            ->call('openCreate')
            ->set('name', 'Nueva Categoría')
            ->call('save')
            ->assertHasNoErrors();

        expect(Category::where('name', 'Nueva Categoría')->exists())->toBeTrue();
    });

    test('validación requiere nombre', function () {
        $user = User::factory()->create();
        Livewire::actingAs($user)
            ->test(Categories::class)
            ->call('openCreate')
            ->call('save')
            ->assertHasErrors(['name']);
    });

    test('edita categoría correctamente', function () {
        $user = User::factory()->create();
        $cat = Category::create(['name' => 'Original', 'slug' => 'original']);

        Livewire::actingAs($user)
            ->test(Categories::class)
            ->call('openEdit', $cat->id)
            ->set('name', 'Modificada')
            ->call('save');

        expect(Category::find($cat->id)->name)->toBe('Modificada');
    });

    test('elimina categoría', function () {
        $user = User::factory()->create();
        $cat = Category::create(['name' => 'Borrar', 'slug' => 'borrar']);

        Livewire::actingAs($user)
            ->test(Categories::class)
            ->call('delete', $cat->id);

        expect(Category::count())->toBe(0);
    });
});

describe('Admin Customers Livewire', function () {
    test('crea cliente correctamente', function () {
        $user = User::factory()->create();
        Livewire::actingAs($user)
            ->test(Customers::class)
            ->call('openCreate')
            ->set('name', 'María López')
            ->set('phone', '5598765432')
            ->call('save')
            ->assertHasNoErrors();

        expect(Customer::where('phone', '5598765432')->exists())->toBeTrue();
    });

    test('validación requiere nombre y teléfono', function () {
        $user = User::factory()->create();
        Livewire::actingAs($user)
            ->test(Customers::class)
            ->call('openCreate')
            ->call('save')
            ->assertHasErrors(['name', 'phone']);
    });
});

describe('Admin Quotes Livewire', function () {
    test('cambia status de cotización', function () {
        $user = User::factory()->create();
        $customer = Customer::create(['name' => 'Juan', 'phone' => '5512345678']);
        $quote = Quote::create(['customer_id' => $customer->id, 'total' => 100]);

        Livewire::actingAs($user)
            ->test(Quotes::class)
            ->call('updateStatus', $quote->id, 'confirmada');

        expect(Quote::find($quote->id)->status)->toBe('confirmada');
    });

    test('marca cotizaciones como vistas al montar', function () {
        $user = User::factory()->create();
        $customer = Customer::create(['name' => 'Juan', 'phone' => '5512345678']);
        Quote::create(['customer_id' => $customer->id, 'total' => 100]);

        expect(Quote::whereNull('seen_at')->count())->toBe(1);

        Livewire::actingAs($user)->test(Quotes::class);

        expect(Quote::whereNull('seen_at')->count())->toBe(0);
    });
});

describe('Admin Inventory Livewire', function () {
    test('registra movimiento de entrada', function () {
        $user = User::factory()->create();
        $cat = Category::create(['name' => 'Cat', 'slug' => 'cat']);
        $product = Product::create(['category_id' => $cat->id, 'name' => 'P1', 'slug' => 'p1', 'price' => 50, 'stock' => 10, 'unit' => 'pieza']);

        Livewire::actingAs($user)
            ->test(Inventory::class)
            ->call('openMovement', $product->id)
            ->set('type', 'entrada')
            ->set('quantity', '20')
            ->set('reason', 'Compra de proveedor')
            ->call('save')
            ->assertHasNoErrors();

        expect(StockMovement::count())->toBe(1)
            ->and(Product::find($product->id)->stock)->toBe(30);
    });

    test('registra movimiento de salida', function () {
        $user = User::factory()->create();
        $cat = Category::create(['name' => 'Cat', 'slug' => 'cat']);
        $product = Product::create(['category_id' => $cat->id, 'name' => 'P1', 'slug' => 'p1', 'price' => 50, 'stock' => 50, 'unit' => 'pieza']);

        Livewire::actingAs($user)
            ->test(Inventory::class)
            ->call('openMovement', $product->id)
            ->set('type', 'salida')
            ->set('quantity', '15')
            ->set('reason', 'Venta')
            ->call('save');

        expect(Product::find($product->id)->stock)->toBe(35);
    });
});

// ============================================================
// PDF
// ============================================================

describe('PDF de cotización', function () {
    test('genera PDF correctamente', function () {
        $user = User::factory()->create();
        $customer = Customer::create(['name' => 'Juan Pérez', 'phone' => '5512345678']);
        $quote = Quote::create(['customer_id' => $customer->id, 'total' => 500]);

        $this->actingAs($user)
            ->get("/admin/quotes/{$quote->id}/pdf")
            ->assertStatus(200)
            ->assertHeader('content-type', 'application/pdf');
    });

    test('PDF de cotización inexistente devuelve 404', function () {
        $user = User::factory()->create();
        $this->actingAs($user)
            ->get('/admin/quotes/99999/pdf')
            ->assertStatus(404);
    });
});
