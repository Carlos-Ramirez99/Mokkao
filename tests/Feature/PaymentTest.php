<?php

namespace Tests\Feature;

use App\Models\Categoria;
use App\Models\Pago;
use App\Models\Producto;
use App\Models\Sucursal;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PaymentTest extends TestCase
{
    use RefreshDatabase;

    public function test_confirming_an_order_creates_a_paid_online_payment(): void
    {
        [$user, $producto, $sucursal] = $this->seedCheckoutData();

        $response = $this
            ->actingAs($user)
            ->withSession([
                'cart' => [
                    $producto->id_producto => [
                        'id_producto' => $producto->id_producto,
                        'nombre' => $producto->nombre,
                        'precio' => 3.50,
                        'cantidad' => 2,
                        'notas' => null,
                    ],
                ],
            ])
            ->post(route('orders.store'), [
                'id_sucursal' => $sucursal->id_sucursal,
                'fecha' => now()->addDay()->toDateString(),
                'hora_recogida' => '18:30',
                'metodo_pago' => 'tarjeta',
            ]);

        $response->assertRedirect();

        $this->assertDatabaseHas('pagos', [
            'metodo_pago' => 'tarjeta',
            'estado' => 'pagado',
            'monto' => 7.00,
        ]);
    }

    public function test_admin_can_see_sales_report(): void
    {
        [$user, $producto, $sucursal] = $this->seedCheckoutData();
        $admin = User::create([
            'nombre' => 'Admin',
            'email' => 'admin@example.test',
            'contrasena' => 'password123',
            'rol' => 'administrador',
        ]);

        $pedido = $user->pedidos()->create([
            'id_sucursal' => $sucursal->id_sucursal,
            'fecha' => now()->addDay()->toDateString(),
            'hora_recogida' => '18:30',
            'estado' => 'pendiente',
            'total' => 3.50,
        ]);

        $pedido->detalles()->create([
            'id_producto' => $producto->id_producto,
            'cantidad' => 1,
            'precio_unitario' => 3.50,
        ]);

        Pago::create([
            'id_pedido' => $pedido->id_pedido,
            'metodo_pago' => 'bizum',
            'monto' => 3.50,
            'estado' => 'pagado',
            'fecha_pago' => now(),
        ]);

        $this
            ->actingAs($admin)
            ->get(route('admin.sales.index'))
            ->assertOk()
            ->assertSee('Reporte de ventas')
            ->assertSee('3.50');
    }

    public function test_admin_can_filter_sales_by_payment_method(): void
    {
        [$user, $producto, $sucursal] = $this->seedCheckoutData();
        $admin = User::create([
            'nombre' => 'Admin',
            'email' => 'admin-filter@example.test',
            'contrasena' => 'password123',
            'rol' => 'administrador',
        ]);

        $pedido = $user->pedidos()->create([
            'id_sucursal' => $sucursal->id_sucursal,
            'fecha' => now()->addDay()->toDateString(),
            'hora_recogida' => '18:30',
            'estado' => 'pendiente',
            'total' => 3.50,
        ]);

        $pedido->detalles()->create([
            'id_producto' => $producto->id_producto,
            'cantidad' => 1,
            'precio_unitario' => 3.50,
        ]);

        Pago::create([
            'id_pedido' => $pedido->id_pedido,
            'metodo_pago' => 'bizum',
            'monto' => 3.50,
            'estado' => 'pagado',
            'fecha_pago' => now(),
        ]);

        $this
            ->actingAs($admin)
            ->get(route('admin.sales.index', ['metodo_pago' => 'tarjeta']))
            ->assertOk()
            ->assertSee('No hay ventas para los filtros seleccionados.');
    }

    private function seedCheckoutData(): array
    {
        $user = User::create([
            'nombre' => 'Cliente',
            'email' => 'cliente@example.test',
            'contrasena' => 'password123',
            'rol' => 'cliente',
        ]);

        $categoria = Categoria::create(['nombre' => 'Café']);

        $producto = Producto::create([
            'id_categoria' => $categoria->id_categoria,
            'nombre' => 'Latte',
            'descripcion' => 'Café con leche',
            'precio' => 3.50,
            'disponible' => true,
        ]);

        $sucursal = Sucursal::create([
            'nombre' => 'Mokkao Centro',
            'direccion' => 'Calle Mayor 10',
        ]);

        return [$user, $producto, $sucursal];
    }
}
