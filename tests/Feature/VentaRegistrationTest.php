<?php

namespace Tests\Feature;

use App\Jobs\SendInvoiceEmailJob;
use App\Models\Cliente;
use App\Models\Factura;
use App\Models\User;
use App\Models\Venta;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class VentaRegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_register_sale_with_customer_invoice_and_total(): void
    {
        Queue::fake();

        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->post(route('ventas.store'), [
                'fecha' => now()->toDateString(),
                'cliente_nombre' => 'Cliente Automatizado',
                'cliente_empresa' => 'Cacao WINI',
                'cliente_identificacion' => '1550011041001',
                'cliente_telefono' => '0999999999',
                'cliente_direccion' => 'Via Tena - Archidona',
                'cliente_correo' => 'cliente.automatizado@example.com',
                'libras' => '12.50',
                'precio_por_libra' => '2.40',
                'metodo_pago' => 'transferencia',
            ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect(route('ventas.index'));

        $cliente = Cliente::query()->where('identificacion', '1550011041001')->firstOrFail();
        $venta = Venta::query()->where('cliente_id', $cliente->id)->firstOrFail();
        $factura = Factura::query()->where('venta_id', $venta->id)->firstOrFail();

        $this->assertSame($user->id, $venta->user_id);
        $this->assertSame('30.00', $venta->total);
        $this->assertSame('transferencia', $venta->metodo_pago);
        $this->assertSame('30.00', $factura->subtotal);
        $this->assertSame('30.00', $factura->total);

        Queue::assertPushed(SendInvoiceEmailJob::class, function (SendInvoiceEmailJob $job) use ($factura) {
            return $job->facturaId === $factura->id;
        });
    }
}
