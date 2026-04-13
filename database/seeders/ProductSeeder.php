<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $hidraulica = Category::where('slug', 'mangueras-hidraulicas')->first()->id;
        $neumatica = Category::where('slug', 'mangueras-neumaticas')->first()->id;
        $conHid = Category::where('slug', 'conexiones-hidraulicas')->first()->id;
        $conNeu = Category::where('slug', 'conexiones-neumaticas')->first()->id;
        $cilindros = Category::where('slug', 'cilindros-actuadores')->first()->id;
        $accesorios = Category::where('slug', 'accesorios-sellos')->first()->id;

        $products = [

            // ── MANGUERAS HIDRÁULICAS ──────────────────────────────────────
            [
                'category_id' => $hidraulica,
                'name' => 'Parker 381 Manguera Hidráulica 1/4" 2SN',
                'slug' => 'parker-381-hidraulica-1-4-2sn',
                'description' => 'Manguera hidráulica Parker serie 381, 2 espirales de acero, 1/4", presión máx. 400 bar. Ideal para maquinaria industrial y agrícola.',
                'price' => 145.00, 'stock' => 300, 'unit' => 'metro',
            ],
            [
                'category_id' => $hidraulica,
                'name' => 'Parker 381 Manguera Hidráulica 3/8" 2SN',
                'slug' => 'parker-381-hidraulica-3-8-2sn',
                'description' => 'Manguera hidráulica Parker serie 381, 2 espirales de acero, 3/8", presión máx. 350 bar.',
                'price' => 185.00, 'stock' => 250, 'unit' => 'metro',
            ],
            [
                'category_id' => $hidraulica,
                'name' => 'Parker 381 Manguera Hidráulica 1/2" 2SN',
                'slug' => 'parker-381-hidraulica-1-2-2sn',
                'description' => 'Manguera hidráulica Parker serie 381, 2 espirales de acero, 1/2", presión máx. 275 bar.',
                'price' => 230.00, 'stock' => 200, 'unit' => 'metro',
            ],
            [
                'category_id' => $hidraulica,
                'name' => 'Parker 381 Manguera Hidráulica 3/4" 2SN',
                'slug' => 'parker-381-hidraulica-3-4-2sn',
                'description' => 'Manguera hidráulica Parker serie 381, 2 espirales de acero, 3/4", presión máx. 215 bar.',
                'price' => 310.00, 'stock' => 150, 'unit' => 'metro',
            ],
            [
                'category_id' => $hidraulica,
                'name' => 'Gates MegaSys MXG 1/4" 4SP',
                'slug' => 'gates-megasys-mxg-1-4-4sp',
                'description' => 'Manguera hidráulica Gates MegaSys MXG, 4 espirales, 1/4", presión máx. 420 bar. Alta resistencia a la abrasión.',
                'price' => 165.00, 'stock' => 200, 'unit' => 'metro',
            ],
            [
                'category_id' => $hidraulica,
                'name' => 'Gates MegaSys MXG 1/2" 4SP',
                'slug' => 'gates-megasys-mxg-1-2-4sp',
                'description' => 'Manguera hidráulica Gates MegaSys MXG, 4 espirales, 1/2", presión máx. 350 bar.',
                'price' => 260.00, 'stock' => 150, 'unit' => 'metro',
            ],
            [
                'category_id' => $hidraulica,
                'name' => 'Alfagomma T51 Manguera Hidráulica 1" 2SN',
                'slug' => 'alfagomma-t51-hidraulica-1-2sn',
                'description' => 'Manguera hidráulica Alfagomma T51, 2 espirales, 1 pulgada, presión máx. 160 bar. Cubierta resistente a ozono y UV.',
                'price' => 420.00, 'stock' => 100, 'unit' => 'metro',
            ],
            [
                'category_id' => $hidraulica,
                'name' => 'Eaton Weatherhead H77 Manguera 3/8" 1SN',
                'slug' => 'eaton-weatherhead-h77-3-8-1sn',
                'description' => 'Manguera hidráulica Eaton Weatherhead H77, 1 espiral de acero, 3/8", presión máx. 225 bar. Flexible y ligera.',
                'price' => 155.00, 'stock' => 180, 'unit' => 'metro',
            ],
            [
                'category_id' => $hidraulica,
                'name' => 'Bosch Rexroth Manguera Hidráulica 1/2" 4SH',
                'slug' => 'bosch-rexroth-hidraulica-1-2-4sh',
                'description' => 'Manguera hidráulica Bosch Rexroth, 4 espirales de acero, 1/2", presión máx. 420 bar. Para aplicaciones de alta presión.',
                'price' => 380.00, 'stock' => 80, 'unit' => 'metro',
            ],

            // ── MANGUERAS NEUMÁTICAS ───────────────────────────────────────
            [
                'category_id' => $neumatica,
                'name' => 'Festo PUN-H 6x1 Manguera Neumática 6mm',
                'slug' => 'festo-pun-h-6x1-neumatica-6mm',
                'description' => 'Manguera neumática Festo PUN-H, poliuretano, 6x1mm, presión máx. 10 bar. Alta flexibilidad y resistencia a dobleces.',
                'price' => 28.00, 'stock' => 1000, 'unit' => 'metro',
            ],
            [
                'category_id' => $neumatica,
                'name' => 'Festo PUN-H 8x1.25 Manguera Neumática 8mm',
                'slug' => 'festo-pun-h-8x1-25-neumatica-8mm',
                'description' => 'Manguera neumática Festo PUN-H, poliuretano, 8x1.25mm, presión máx. 10 bar.',
                'price' => 35.00, 'stock' => 800, 'unit' => 'metro',
            ],
            [
                'category_id' => $neumatica,
                'name' => 'Festo PUN-H 10x1.5 Manguera Neumática 10mm',
                'slug' => 'festo-pun-h-10x1-5-neumatica-10mm',
                'description' => 'Manguera neumática Festo PUN-H, poliuretano, 10x1.5mm, presión máx. 10 bar.',
                'price' => 42.00, 'stock' => 600, 'unit' => 'metro',
            ],
            [
                'category_id' => $neumatica,
                'name' => 'SMC TU0604 Manguera Neumática 6mm Azul',
                'slug' => 'smc-tu0604-neumatica-6mm-azul',
                'description' => 'Manguera neumática SMC TU0604, poliuretano azul, 6x4mm, presión máx. 10 bar. Resistente a aceites y productos químicos.',
                'price' => 26.00, 'stock' => 900, 'unit' => 'metro',
            ],
            [
                'category_id' => $neumatica,
                'name' => 'SMC TU0805 Manguera Neumática 8mm Azul',
                'slug' => 'smc-tu0805-neumatica-8mm-azul',
                'description' => 'Manguera neumática SMC TU0805, poliuretano azul, 8x5mm, presión máx. 10 bar.',
                'price' => 33.00, 'stock' => 700, 'unit' => 'metro',
            ],
            [
                'category_id' => $neumatica,
                'name' => 'Parker Parflex 1/4" Manguera Neumática Nylon',
                'slug' => 'parker-parflex-1-4-neumatica-nylon',
                'description' => 'Manguera neumática Parker Parflex, nylon PA12, 1/4", presión máx. 12 bar. Excelente resistencia química.',
                'price' => 38.00, 'stock' => 500, 'unit' => 'metro',
            ],
            [
                'category_id' => $neumatica,
                'name' => 'Camozzi 9-1/4 Manguera Neumática 1/4" Espiral',
                'slug' => 'camozzi-9-1-4-neumatica-espiral',
                'description' => 'Manguera neumática espiral Camozzi, poliuretano, 1/4", longitud extendida 10m. Retráctil, ideal para herramientas neumáticas.',
                'price' => 320.00, 'stock' => 50, 'unit' => 'pieza',
            ],

            // ── CONEXIONES HIDRÁULICAS ─────────────────────────────────────
            [
                'category_id' => $conHid,
                'name' => 'Parker JIC 37° Macho 1/4" - 1/4" NPT',
                'slug' => 'parker-jic-macho-1-4-npt',
                'description' => 'Conector recto JIC 37° macho Parker, 1/4" JIC x 1/4" NPT, acero al carbono zincado. Para sistemas hidráulicos de media y alta presión.',
                'price' => 68.00, 'stock' => 200, 'unit' => 'pieza',
            ],
            [
                'category_id' => $conHid,
                'name' => 'Parker JIC 37° Macho 3/8" - 3/8" NPT',
                'slug' => 'parker-jic-macho-3-8-npt',
                'description' => 'Conector recto JIC 37° macho Parker, 3/8" JIC x 3/8" NPT, acero al carbono zincado.',
                'price' => 82.00, 'stock' => 180, 'unit' => 'pieza',
            ],
            [
                'category_id' => $conHid,
                'name' => 'Parker JIC 37° Macho 1/2" - 1/2" NPT',
                'slug' => 'parker-jic-macho-1-2-npt',
                'description' => 'Conector recto JIC 37° macho Parker, 1/2" JIC x 1/2" NPT, acero al carbono zincado.',
                'price' => 98.00, 'stock' => 150, 'unit' => 'pieza',
            ],
            [
                'category_id' => $conHid,
                'name' => 'Codo 90° JIC 1/4" Acero Zincado',
                'slug' => 'codo-90-jic-1-4-acero',
                'description' => 'Codo 90° JIC 37° macho, 1/4", acero al carbono zincado. Para cambios de dirección en líneas hidráulicas.',
                'price' => 95.00, 'stock' => 120, 'unit' => 'pieza',
            ],
            [
                'category_id' => $conHid,
                'name' => 'Codo 90° JIC 3/8" Acero Zincado',
                'slug' => 'codo-90-jic-3-8-acero',
                'description' => 'Codo 90° JIC 37° macho, 3/8", acero al carbono zincado.',
                'price' => 115.00, 'stock' => 100, 'unit' => 'pieza',
            ],
            [
                'category_id' => $conHid,
                'name' => 'Adaptador BSP 1/4" a NPT 1/4"',
                'slug' => 'adaptador-bsp-1-4-npt-1-4',
                'description' => 'Adaptador macho BSP 1/4" a macho NPT 1/4", acero zincado. Para conexión entre sistemas métricos e imperiales.',
                'price' => 72.00, 'stock' => 150, 'unit' => 'pieza',
            ],
            [
                'category_id' => $conHid,
                'name' => 'Niple Recto NPT 3/8" Acero',
                'slug' => 'niple-recto-npt-3-8-acero',
                'description' => 'Niple recto NPT 3/8" macho-macho, acero al carbono. Para unión de dos líneas hidráulicas.',
                'price' => 55.00, 'stock' => 200, 'unit' => 'pieza',
            ],
            [
                'category_id' => $conHid,
                'name' => 'Tee NPT 1/2" Acero Hidráulica',
                'slug' => 'tee-npt-1-2-acero-hidraulica',
                'description' => 'Tee NPT 1/2" hembra, acero al carbono zincado. Para derivaciones en sistemas hidráulicos.',
                'price' => 135.00, 'stock' => 80, 'unit' => 'pieza',
            ],
            [
                'category_id' => $conHid,
                'name' => 'Fitting Orbitrol BSP 3/4" Parker',
                'slug' => 'fitting-orbitrol-bsp-3-4-parker',
                'description' => 'Fitting Parker para orbitrol BSP 3/4", acero zincado. Compatible con sistemas de dirección hidráulica.',
                'price' => 185.00, 'stock' => 60, 'unit' => 'pieza',
            ],

            // ── CONEXIONES NEUMÁTICAS ──────────────────────────────────────
            [
                'category_id' => $conNeu,
                'name' => 'Festo QSS-6 Racor Rápido Recto 6mm',
                'slug' => 'festo-qss-6-racor-rapido-6mm',
                'description' => 'Racor de conexión rápida Festo QSS-6, recto, para manguera 6mm, cuerpo plástico. Conexión y desconexión sin herramienta.',
                'price' => 48.00, 'stock' => 300, 'unit' => 'pieza',
            ],
            [
                'category_id' => $conNeu,
                'name' => 'Festo QSS-8 Racor Rápido Recto 8mm',
                'slug' => 'festo-qss-8-racor-rapido-8mm',
                'description' => 'Racor de conexión rápida Festo QSS-8, recto, para manguera 8mm, cuerpo plástico.',
                'price' => 52.00, 'stock' => 250, 'unit' => 'pieza',
            ],
            [
                'category_id' => $conNeu,
                'name' => 'Festo QSL-6 Racor Rápido Codo 6mm',
                'slug' => 'festo-qsl-6-racor-codo-6mm',
                'description' => 'Racor de conexión rápida Festo QSL-6, codo 90°, para manguera 6mm. Ideal para espacios reducidos.',
                'price' => 58.00, 'stock' => 200, 'unit' => 'pieza',
            ],
            [
                'category_id' => $conNeu,
                'name' => 'SMC KQ2H06-01S Racor Recto 6mm - 1/8" NPT',
                'slug' => 'smc-kq2h06-01s-racor-6mm-npt',
                'description' => 'Racor recto SMC KQ2H, 6mm x 1/8" NPT, cuerpo de latón niquelado. Alta resistencia a la corrosión.',
                'price' => 62.00, 'stock' => 200, 'unit' => 'pieza',
            ],
            [
                'category_id' => $conNeu,
                'name' => 'SMC KQ2L08-02S Racor Codo 8mm - 1/4" NPT',
                'slug' => 'smc-kq2l08-02s-racor-codo-8mm',
                'description' => 'Racor codo 90° SMC KQ2L, 8mm x 1/4" NPT, cuerpo de latón niquelado.',
                'price' => 75.00, 'stock' => 150, 'unit' => 'pieza',
            ],
            [
                'category_id' => $conNeu,
                'name' => 'Parker Legris 3109 Racor Rápido 1/4" NPT',
                'slug' => 'parker-legris-3109-racor-1-4-npt',
                'description' => 'Racor de conexión rápida Parker Legris serie 3109, 1/4" NPT, cuerpo de latón. Para mangueras de 6 a 8mm.',
                'price' => 85.00, 'stock' => 120, 'unit' => 'pieza',
            ],
            [
                'category_id' => $conNeu,
                'name' => 'Camozzi 6520 Racor Rápido Tee 6mm',
                'slug' => 'camozzi-6520-racor-tee-6mm',
                'description' => 'Racor en T Camozzi serie 6520, para manguera 6mm, cuerpo plástico. Para derivaciones en circuitos neumáticos.',
                'price' => 68.00, 'stock' => 100, 'unit' => 'pieza',
            ],

            // ── CILINDROS Y ACTUADORES ─────────────────────────────────────
            [
                'category_id' => $cilindros,
                'name' => 'Festo DSBC-32-100-PPVA-N3 Cilindro Neumático',
                'slug' => 'festo-dsbc-32-100-cilindro-neumatico',
                'description' => 'Cilindro neumático Festo DSBC, doble efecto, diámetro 32mm, carrera 100mm. Norma ISO 15552. Amortiguación neumática regulable.',
                'price' => 2850.00, 'stock' => 15, 'unit' => 'pieza',
            ],
            [
                'category_id' => $cilindros,
                'name' => 'SMC CDM2B32-100 Cilindro Neumático 32mm',
                'slug' => 'smc-cdm2b32-100-cilindro-32mm',
                'description' => 'Cilindro neumático SMC serie CM2, doble efecto, diámetro 32mm, carrera 100mm. Cuerpo de aluminio anodizado.',
                'price' => 3200.00, 'stock' => 10, 'unit' => 'pieza',
            ],
            [
                'category_id' => $cilindros,
                'name' => 'Parker P1D-S050MS-0100 Cilindro ISO 50mm',
                'slug' => 'parker-p1d-s050ms-0100-cilindro-50mm',
                'description' => 'Cilindro neumático Parker serie P1D, doble efecto, diámetro 50mm, carrera 100mm. Norma ISO 15552.',
                'price' => 4500.00, 'stock' => 8, 'unit' => 'pieza',
            ],
            [
                'category_id' => $cilindros,
                'name' => 'Kit de Sellos Cilindro Neumático 32mm',
                'slug' => 'kit-sellos-cilindro-neumatico-32mm',
                'description' => 'Kit completo de sellos para cilindro neumático diámetro 32mm. Incluye sello de émbolo, sello de vástago y anillo raspador. Compatible con Festo, SMC, Parker.',
                'price' => 380.00, 'stock' => 40, 'unit' => 'juego',
            ],
            [
                'category_id' => $cilindros,
                'name' => 'Kit de Sellos Cilindro Neumático 50mm',
                'slug' => 'kit-sellos-cilindro-neumatico-50mm',
                'description' => 'Kit completo de sellos para cilindro neumático diámetro 50mm. Compatible con Festo, SMC, Parker, Camozzi.',
                'price' => 520.00, 'stock' => 30, 'unit' => 'juego',
            ],
            [
                'category_id' => $cilindros,
                'name' => 'Cilindro Hidráulico Doble Vástago 11/2" x 3/4"',
                'slug' => 'cilindro-hidraulico-doble-vastago-1-5-x-3-4',
                'description' => 'Cilindro hidráulico de doble vástago, diámetro 1-1/2", vástago 3/4", carrera variable. Fabricación nacional. Incluye fabricación de sellos y seguros.',
                'price' => 2400.00, 'stock' => 5, 'unit' => 'pieza',
            ],

            // ── ACCESORIOS Y SELLOS ────────────────────────────────────────
            [
                'category_id' => $accesorios,
                'name' => 'Válvula de Bola NPT 1/4" Acero Inoxidable',
                'slug' => 'valvula-bola-npt-1-4-acero-inox',
                'description' => 'Válvula de bola NPT 1/4" de acero inoxidable 316. Presión máx. 1000 PSI. Para fluidos hidráulicos, agua y aire.',
                'price' => 285.00, 'stock' => 50, 'unit' => 'pieza',
            ],
            [
                'category_id' => $accesorios,
                'name' => 'Válvula de Bola NPT 1/2" Acero Inoxidable',
                'slug' => 'valvula-bola-npt-1-2-acero-inox',
                'description' => 'Válvula de bola NPT 1/2" de acero inoxidable 316. Presión máx. 1000 PSI.',
                'price' => 385.00, 'stock' => 40, 'unit' => 'pieza',
            ],
            [
                'category_id' => $accesorios,
                'name' => 'Manómetro Glicerina 0-160 bar 1/4" NPT',
                'slug' => 'manometro-glicerina-0-160-bar-1-4-npt',
                'description' => 'Manómetro relleno de glicerina, rango 0-160 bar, conexión 1/4" NPT inferior, esfera 63mm. Para sistemas hidráulicos con vibración.',
                'price' => 420.00, 'stock' => 30, 'unit' => 'pieza',
            ],
            [
                'category_id' => $accesorios,
                'name' => 'Manómetro Glicerina 0-10 bar 1/4" NPT',
                'slug' => 'manometro-glicerina-0-10-bar-1-4-npt',
                'description' => 'Manómetro relleno de glicerina, rango 0-10 bar, conexión 1/4" NPT inferior, esfera 63mm. Para sistemas neumáticos.',
                'price' => 380.00, 'stock' => 30, 'unit' => 'pieza',
            ],
            [
                'category_id' => $accesorios,
                'name' => 'Sello Hidráulico Parker O-Ring 1/4" NBR',
                'slug' => 'sello-hidraulico-parker-oring-1-4-nbr',
                'description' => 'O-Ring Parker NBR (nitrilo) 1/4", dureza 70 Shore A. Resistente a aceites hidráulicos, combustibles y agua. Paquete de 10 piezas.',
                'price' => 45.00, 'stock' => 500, 'unit' => 'paquete',
            ],
            [
                'category_id' => $accesorios,
                'name' => 'Cinta Teflón PTFE 3/4" Industrial',
                'slug' => 'cinta-teflon-ptfe-3-4-industrial',
                'description' => 'Cinta de teflón PTFE 3/4" x 10m, densidad 0.35 g/cm³. Para sellado de roscas en sistemas hidráulicos y neumáticos.',
                'price' => 18.00, 'stock' => 400, 'unit' => 'pieza',
            ],
            [
                'category_id' => $accesorios,
                'name' => 'Filtro Regulador Festo LFR-1/4-D-MINI',
                'slug' => 'filtro-regulador-festo-lfr-1-4-d-mini',
                'description' => 'Unidad de mantenimiento Festo LFR, filtro + regulador, 1/4" NPT, presión 0.5-16 bar, filtración 40 micras. Para preparación de aire comprimido.',
                'price' => 1850.00, 'stock' => 15, 'unit' => 'pieza',
            ],
            [
                'category_id' => $accesorios,
                'name' => 'Abrazadera Acero Inoxidable 1" (25-40mm)',
                'slug' => 'abrazadera-acero-inox-1-25-40mm',
                'description' => 'Abrazadera de acero inoxidable 304, rango 25-40mm (1"). Tornillo hexagonal. Para sujeción de mangueras hidráulicas y neumáticas.',
                'price' => 22.00, 'stock' => 300, 'unit' => 'pieza',
            ],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
}
