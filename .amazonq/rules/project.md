# Proyecto: Mangueras y Conexiones MAE

## Descripción
Página web para un negocio de mangueras y conexiones hidráulicas ubicado en México.
El objetivo es tener un sitio completo con catálogo, cotizaciones, inventario, clientes y panel de administración.

## Stack
- Backend: Laravel (PHP)
- Frontend: Livewire + Blade
- CSS: Tailwind CSS
- Servidor local: Laravel Herd

## Identidad visual
- Nombre: Mangueras y Conexiones MAE
- Colores: negro, blanco, naranja, gris
- Logo: `public/images/logo.png`

## Funcionalidades requeridas
- Catálogo de productos (mangueras, conexiones, accesorios)
- Cotizaciones (sin pagos en línea, confirmación manual)
- Inventario
- Clientes
- Panel de administración

## Decisiones técnicas
- Sin pagos en línea, solo cotizaciones manuales
- Mercado objetivo: México (moneda MXN, español)
- Livewire para interactividad sin necesidad de Vue/React

## Modelos principales (por definir/implementar)
- Product (nombre, descripción, precio, stock, categoría, imagen)
- Category (mangueras, conexiones, accesorios)
- Customer (nombre, empresa, teléfono, email, dirección)
- Quote (cotización con cliente, productos, cantidades, total)
- QuoteItem (producto, cantidad, precio unitario)

## Estado del proyecto
- [ ] Modelos y migraciones
- [ ] Seeders / datos de prueba
- [ ] Catálogo público
- [ ] Panel de administración
- [ ] Módulo de cotizaciones
- [ ] Módulo de clientes
- [ ] Módulo de inventario
