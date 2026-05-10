<?php

namespace Database\Seeders;

use App\Models\Cliente;
use App\Models\Gasto;
use App\Models\Setting;
use App\Models\User;
use App\Models\Venta;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(['email' => 'admin@wini.local'], [
            'name' => 'Administrador Wini',
            'role' => 'admin',
            'password' => Hash::make('password'),
        ]);

        $admin = User::where('email', 'admin@wini.local')->firstOrFail();

        $cliente = Cliente::updateOrCreate([
            'nombre' => 'Compras Andes',
            'empresa' => 'Andes Export S.A.',
        ], [
            'telefono' => '0999999999',
        ]);

        Venta::updateOrCreate([
            'cliente_id' => $cliente->id,
            'fecha' => now()->toDateString(),
        ], [
            'user_id' => $admin->id,
            'libras' => 240.50,
            'precio_por_libra' => 1.35,
            'total' => round(240.50 * 1.35, 2),
            'metodo_pago' => 'transferencia',
        ]);

        Gasto::updateOrCreate([
            'fecha' => now()->toDateString(),
            'tipo' => 'transporte',
        ], [
            'user_id' => $admin->id,
            'descripcion' => 'Traslado de cacao al centro de acopio',
            'monto' => 28.00,
        ]);

        Setting::setValue('company_name', 'Wini');
        Setting::setValue('currency', 'USD');
        Setting::setValue('report_footer', 'Producto sostenible');
    }
}
