# Proyecto: Mangueras y Conexiones MAE

## Descripción
Página web para un negocio de mangueras y conexiones hidráulicas ubicado en México.
Sitio completo con catálogo público, cotizaciones, inventario, clientes y panel de administración.

## Stack
- Backend: Laravel (PHP)
- Frontend: Livewire + Blade
- CSS: Tailwind CSS v4
- Servidor local: Laravel Herd
- Base de datos: SQLite (desarrollo)

## Identidad visual
- Nombre: Mangueras y Conexiones MAE
- Colores: azul #1e3a5f, naranja #f97316, blanco, gris
- Logo: `public/images/logo.png`
- WhatsApp/Teléfono: +52 55 4230 5373

## Rutas principales
- `/` — Catálogo público (sin login)
- `/admin/login` — Login del panel
- `/admin/dashboard` — Dashboard
- `/admin/categories` — CRUD categorías
- `/admin/products` — CRUD productos
- `/admin/customers` — CRUD clientes
- `/admin/inventory` — Movimientos de stock
- `/admin/quotes` — Listado cotizaciones
- `/admin/quotes/create` — Nueva cotización
- `/admin/quotes/{quote}` — Detalle cotización
- `/admin/quotes/{quote}/pdf` — PDF de cotización (QuotePdfController)

## Usuario admin
- Email: admin@mae.mx
- Password: admin123

## Modelos y relaciones
- `Category` — hasMany Products
- `Product` — belongsTo Category, hasMany QuoteItems, hasMany StockMovements | campos: sku, name, slug, description, application, price, stock, unit, image, active
- `Customer` — hasMany Quotes | campos: name, company, rfc, phone, email, address, city, zip_code, notes
- `Quote` — belongsTo Customer, hasMany QuoteItems | campos: folio (MAE-0001), status (pendiente/confirmada/cancelada), total, notes, delivery_time, conditions, seen_at
- `QuoteItem` — belongsTo Quote, belongsTo Product | campos: quantity, unit_price, subtotal, delivery_time
- `StockMovement` — belongsTo Product | campos: type (entrada/salida), quantity, reason, notes

## Componentes Livewire
### Admin (`app/Livewire/Admin/`)
- `Login` — formulario de login con session regenerate
- `Dashboard` — estadísticas y cotizaciones recientes
- `Categories` — CRUD con modal
- `Products` — CRUD con modal + subida de imágenes + campo application
- `Customers` — CRUD con modal
- `Inventory` — movimientos de stock (entradas/salidas)
- `Quotes` — listado con cambio de status inline, marca seen_at al montar
- `QuoteCreate` — formulario con items dinámicos
- `QuoteShow` — detalle con cambio de status
- `QuoteNotifications` — polling cada 3s, banner + toast al recibir cotización nueva

### Controladores HTTP (`app/Http/Controllers/Admin/`)
- `QuotePdfController` — genera PDF de cotización con DomPDF

### Catálogo (`app/Livewire/Catalog/`)
- `ProductCatalog` — nav horizontal tipo pills sticky, grid de productos, modal de detalle Alpine.js
- `QuoteCart` — carrito lateral con Alpine entangle, formulario de envío, sin precios públicos

## Layouts
- `layouts/admin.blade.php` — sidebar negro/naranja, topbar
- `layouts/auth.blade.php` — fondo oscuro para login
- `layouts/public.blade.php` — navbar, footer, botón flotante WhatsApp

## Decisiones técnicas
- Sin pagos en línea, solo cotizaciones manuales
- Mercado objetivo: México (moneda MXN, español)
- Livewire para interactividad sin Vue/React
- Alpine.js gestionado por Livewire 3 (no importar manualmente)
- Folios de cotización generados automáticamente: MAE-0001, MAE-0002...
- Imágenes de productos en `storage/public/products`
- Cliente se crea automáticamente desde el catálogo público si no existe (por teléfono)
- Eventos entre componentes Livewire via `Livewire.dispatch()` desde Alpine
- Email configurado con Gmail SMTP (erickliconamorales2016@gmail.com → conexiones.mangueras@hotmail.com)
- Índices en tabla products: (active, category_id) y name

## Estado del proyecto
- [x] Modelos y migraciones
- [x] Seeders / datos de prueba
- [x] Catálogo público
- [x] Panel de administración
- [x] Módulo de cotizaciones
- [x] Módulo de clientes
- [x] Módulo de inventario (movimientos de stock)
- [x] Impresión / PDF de cotizaciones
- [x] Email SMTP configurado (Gmail → Hotmail) al recibir cotización
- [x] Logo del negocio (`public/images/logo.png`)
- [x] Diseño visual con colores del logo (azul #1e3a5f, naranja, gris)
- [x] Imagen de fondo industrial en hero
- [x] PDF rediseñado con logo, servicios, IVA, tiempo de entrega y condiciones comerciales
- [x] Campos RFC y CP en clientes
- [x] Tiempo de entrega por producto en cotizaciones
- [x] Condiciones comerciales editables en cotizaciones
- [x] Precios ocultos en catálogo público (solo cotizaciones)
- [x] Modal de detalle de producto en catálogo (descripción + aplicación) vía Alpine.js
- [x] Campo `application` en productos
- [x] Imágenes de productos con `object-contain` en catálogo
- [x] Nav de categorías horizontal tipo pills con iconos SVG por categoría
- [x] Notificaciones en admin: badge sidebar + banner + toast con polling cada 3s
- [x] Campo `seen_at` en quotes para tracking de notificaciones
- [x] Índices en tabla products para optimizar queries
- [x] Tests robustos con Pest: 47 tests, 95 assertions (100% passing)
- [x] `Model::preventLazyLoading()` activo en desarrollo
- [ ] Notificaciones por WhatsApp (Twilio, postergado)
- [ ] Configurar dominio y servidor de producción
