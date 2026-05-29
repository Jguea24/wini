<?php

namespace Database\Seeders;

use App\Models\Cliente;
use App\Models\Gasto;
use App\Models\Inversion;
use App\Models\Setting;
use App\Models\User;
use App\Models\Venta;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $adminEmail = env('ADMIN_EMAIL', 'admin@wini.local');

        User::updateOrCreate(['email' => $adminEmail], [
            'name' => env('ADMIN_NAME', 'Administrador Wini'),
            'role' => 'admin',
            'password' => Hash::make(env('ADMIN_PASSWORD', 'password')),
        ]);

        $admin = User::where('email', $adminEmail)->firstOrFail();

        $cliente = Cliente::updateOrCreate([
            'nombre' => 'Intermediarios Andes',
            'empresa' => 'Andes Export S.A.',
        ], [
            'identificacion' => '1799999999001',
            'telefono' => '0999999999',
            'direccion' => 'Tena, Napo',
            'correo' => 'ventas@andes.example',
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

        Inversion::updateOrCreate([
            'fecha' => now()->toDateString(),
            'tipo' => 'equipos',
            'concepto' => 'Balanza digital',
        ], [
            'user_id' => $admin->id,
            'created_by' => $admin->id,
            'descripcion' => 'Equipo para controlar peso de cacao vendido',
            'monto' => 85.00,
        ]);

        Setting::setValue('company_name', 'Wini');
        Setting::setValue('company_ruc', '0000000000001');
        Setting::setValue('company_address', 'Tena, Napo, Ecuador');
        Setting::setValue('company_phone', '0999999999');
        Setting::setValue('company_email', $adminEmail);
        Setting::setValue('currency', 'USD');
        Setting::setValue('report_footer', 'Producto sostenible');
        Setting::setValue('invoice_prefix', 'FAC');
        Setting::setValue('invoice_next_number', Setting::getValue('invoice_next_number', '1'));
        Setting::setValue('invoice_tax_rate', Setting::getValue('invoice_tax_rate', '0'));
    }
}
