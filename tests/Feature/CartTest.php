<?php

namespace Tests\Feature;

use App\Models\Categoria;
use App\Models\Producto;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CartTest extends TestCase
{
    use RefreshDatabase;

    public function test_adding_a_product_returns_to_previous_page_and_opens_preview(): void
    {
        $producto = $this->createProduct();

        $response = $this
            ->from(route('menu.index'))
            ->post(route('cart.store'), [
                'id_producto' => $producto->id_producto,
                'cantidad' => 1,
                'notas' => '',
            ]);

        $response
            ->assertRedirect(route('menu.index'))
            ->assertSessionHas('cart_preview', true)
            ->assertSessionHas("cart.{$producto->id_producto}.nombre", 'Latte');
    }

    public function test_updating_quantity_from_preview_keeps_preview_open(): void
    {
        $producto = $this->createProduct();

        $response = $this
            ->withSession([
                'cart' => [
                    $producto->id_producto => [
                        'id_producto' => $producto->id_producto,
                        'nombre' => 'Latte',
                        'precio' => 3.50,
                        'cantidad' => 1,
                        'notas' => null,
                    ],
                ],
            ])
            ->from(route('menu.index'))
            ->patch(route('cart.update', $producto->id_producto), [
                'cantidad' => 3,
                'notas' => '',
                'cart_preview' => 1,
            ]);

        $response
            ->assertRedirect(route('menu.index'))
            ->assertSessionHas('cart_preview', true)
            ->assertSessionHas("cart.{$producto->id_producto}.cantidad", 3);
    }

    public function test_updating_quantity_can_return_json_for_silent_refresh(): void
    {
        $producto = $this->createProduct();

        $response = $this
            ->withSession([
                'cart' => [
                    $producto->id_producto => [
                        'id_producto' => $producto->id_producto,
                        'nombre' => 'Latte',
                        'precio' => 3.50,
                        'cantidad' => 1,
                        'notas' => null,
                    ],
                ],
            ])
            ->patchJson(route('cart.update', $producto->id_producto), [
                'cantidad' => 4,
                'notas' => '',
            ]);

        $response
            ->assertOk()
            ->assertJsonPath('cart_count', 4)
            ->assertJsonPath('item.quantity', 4)
            ->assertJsonPath('item.subtotal_formatted', '14.00 €')
            ->assertJsonPath('total_formatted', '14.00 €');
    }

    public function test_removing_a_product_can_return_json_for_silent_refresh(): void
    {
        $producto = $this->createProduct();

        $response = $this
            ->withSession([
                'cart' => [
                    $producto->id_producto => [
                        'id_producto' => $producto->id_producto,
                        'nombre' => 'Latte',
                        'precio' => 3.50,
                        'cantidad' => 2,
                        'notas' => null,
                    ],
                ],
            ])
            ->deleteJson(route('cart.destroy', $producto->id_producto));

        $response
            ->assertOk()
            ->assertJsonPath('cart_count', 0)
            ->assertJsonPath('removed_item_id', $producto->id_producto)
            ->assertJsonPath('is_empty', true)
            ->assertJsonPath('total_formatted', '0.00 €');

        $this->assertSame([], session('cart'));
    }

    private function createProduct(): Producto
    {
        $categoria = Categoria::create([
            'nombre' => 'Cafe',
        ]);

        return Producto::create([
            'id_categoria' => $categoria->id_categoria,
            'nombre' => 'Latte',
            'descripcion' => 'Cafe con leche',
            'precio' => 3.50,
            'disponible' => true,
        ]);
    }
}
