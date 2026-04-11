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
- Colores: negro, blanco, naranja, gris
- Logo: `public/images/logo.png`
- WhatsApp/Teléfono: +52 55 4230 5373

## Rutas principales
- `/` — Catálogo público (sin login)
- `/admin/login` — Login del panel
- `/admin/dashboard` — Dashboard
- `/admin/categories` — CRUD categorías
- `/admin/products` — CRUD productos
- `/admin/customers` — CRUD clientes
- `/admin/quotes` — Listado cotizaciones
- `/admin/quotes/create` — Nueva cotización
- `/admin/quotes/{quote}` — Detalle cotización

## Usuario admin
- Email: admin@mae.mx
- Password: admin123

## Modelos y relaciones
- `Category` — hasMany Products
- `Product` — belongsTo Category, hasMany QuoteItems | campos: name, slug, description, price, stock, unit, image, active
- `Customer` — hasMany Quotes | campos: name, company, rfc, phone, email, address, city, zip_code, notes
- `Quote` — belongsTo Customer, hasMany QuoteItems | campos: folio (MAE-0001), status (pendiente/confirmada/cancelada), total, notes, delivery_time, conditions
- `QuoteItem` — belongsTo Quote, belongsTo Product | campos: quantity, unit_price, subtotal, delivery_time
- `StockMovement` — belongsTo Product | campos: type (entrada/salida), quantity, reason, notes

## Componentes Livewire
### Admin (`app/Livewire/Admin/`)
- `Login` — formulario de login
- `Dashboard` — estadísticas y cotizaciones recientes
- `Categories` — CRUD con modal
- `Products` — CRUD con modal + subida de imágenes
- `Customers` — CRUD con modal
- `Quotes` — listado con cambio de status inline
- `QuoteCreate` — formulario con items dinámicos
- `QuoteShow` — detalle con cambio de status

### Catálogo (`app/Livewire/Catalog/`)
- `ProductCatalog` — grid con filtro por categoría y búsqueda
- `QuoteCart` — carrito lateral con formulario de envío

## Layouts
- `layouts/admin.blade.php` — sidebar negro/naranja, topbar
- `layouts/auth.blade.php` — fondo oscuro para login
- `layouts/public.blade.php` — navbar, footer, botón flotante WhatsApp

## Decisiones técnicas
- Sin pagos en línea, solo cotizaciones manuales
- Mercado objetivo: México (moneda MXN, español)
- Livewire para interactividad sin Vue/React
- Folios de cotización generados automáticamente: MAE-0001, MAE-0002...
- Imágenes de productos en `storage/public/products`
- Cliente se crea automáticamente desde el catálogo público si no existe (por teléfono)

## Estado del proyecto
- [x] Modelos y migraciones
- [x] Seeders / datos de prueba
- [x] Catálogo público
- [x] Panel de administración
- [x] Módulo de cotizaciones
- [x] Módulo de clientes
- [x] Módulo de inventario (movimientos de stock)
- [x] Impresión / PDF de cotizaciones
- [x] Notificaciones por email al recibir cotización (modo log, pendiente configurar SMTP)
- [x] Logo del negocio (`public/images/logo.png`)
- [x] Diseño visual con colores del logo (azul #1e3a5f, naranja, gris)
- [x] Imagen de fondo industrial en hero y login
- [x] PDF rediseñado con logo, servicios, IVA, tiempo de entrega y condiciones comerciales
- [x] Campos RFC y CP en clientes
- [x] Tiempo de entrega por producto en cotizaciones
- [x] Condiciones comerciales editables en cotizaciones
- [ ] Notificaciones por WhatsApp (Twilio, postergado)
- [ ] Configurar SMTP para envío real de emails
