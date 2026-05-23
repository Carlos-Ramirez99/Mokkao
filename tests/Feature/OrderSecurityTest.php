<?php

namespace Tests\Feature;

use App\Models\Categoria;
use App\Models\Pedido;
use App\Models\Producto;
use App\Models\Sucursal;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderSecurityTest extends TestCase
{
    use RefreshDatabase;

    public function test_customer_cannot_view_another_customers_order(): void
    {
        [$owner, $producto, $sucursal] = $this->seedOrderData('owner@example.test');
        $other = User::create([
            'nombre' => 'Otro cliente',
            'email' => 'other@example.test',
            'contrasena' => 'password123',
            'rol' => 'cliente',
        ]);

        $pedido = $this->createOrderFor($owner, $producto, $sucursal);

        $this
            ->actingAs($other)
            ->get(route('orders.show', $pedido))
            ->assertForbidden();
    }

    public function test_customer_cannot_access_admin_sales_report(): void
    {
        $cliente = User::create([
            'nombre' => 'Cliente',
            'email' => 'cliente-security@example.test',
            'contrasena' => 'password123',
            'rol' => 'cliente',
        ]);

        $this
            ->actingAs($cliente)
            ->get(route('admin.sales.index'))
            ->assertForbidden();
    }

    public function test_cash_payment_is_registered_as_pending(): void
    {
        [$user, $producto, $sucursal] = $this->seedOrderData('cash@example.test');

        $this
            ->actingAs($user)
            ->withSession([
                'cart' => [
                    $producto->id_producto => [
                        'id_producto' => $producto->id_producto,
                        'nombre' => $producto->nombre,
                        'precio' => 3.50,
                        'cantidad' => 1,
                        'notas' => null,
                    ],
                ],
            ])
            ->post(route('orders.store'), [
                'id_sucursal' => $sucursal->id_sucursal,
                'fecha' => now()->addDay()->toDateString(),
                'hora_recogida' => '18:30',
                'metodo_pago' => 'efectivo',
            ]);

        $this->assertDatabaseHas('pagos', [
            'metodo_pago' => 'efectivo',
            'estado' => 'pendiente',
            'monto' => 3.50,
        ]);
    }

    private function seedOrderData(string $email): array
    {
        $user = User::create([
            'nombre' => 'Cliente',
            'email' => $email,
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

    private function createOrderFor(User $user, Producto $producto, Sucursal $sucursal): Pedido
    {
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

        return $pedido;
    }
}
