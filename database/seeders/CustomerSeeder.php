<?php

namespace Database\Seeders;

use App\Models\Customer;
use Illuminate\Database\Seeder;

class CustomerSeeder extends Seeder
{
    public function run(): void
    {
        $customers = [
            ['name' => 'Carlos Ramírez', 'company' => 'Construcciones Ramírez SA', 'phone' => '8112345678', 'email' => 'carlos@construccionesramirez.com', 'city' => 'Monterrey'],
            ['name' => 'Laura Mendoza', 'company' => 'Taller Hidráulico del Norte', 'phone' => '8187654321', 'email' => 'laura@tallernorte.mx', 'city' => 'Guadalupe'],
            ['name' => 'Roberto Sánchez', 'company' => null, 'phone' => '8191112233', 'email' => null, 'city' => 'San Nicolás'],
            ['name' => 'Industrias Garza SA de CV', 'company' => 'Industrias Garza', 'phone' => '8123456789', 'email' => 'compras@industriasgarza.com', 'city' => 'Apodaca'],
            ['name' => 'Miguel Torres', 'company' => 'Mantenimiento Industrial Torres', 'phone' => '8198765432', 'email' => 'mtorres@mitindustrial.com', 'city' => 'Monterrey'],
        ];

        foreach ($customers as $customer) {
            Customer::create($customer);
        }
    }
}
