<?php

namespace Tests\Feature;

use App\Models\Categoria;
use App\Models\Producto;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CategoryAdminTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_delete_an_empty_category(): void
    {
        $admin = $this->admin();
        $categoria = Categoria::create(['nombre' => 'Temporada']);

        $response = $this
            ->actingAs($admin)
            ->delete(route('admin.categories.destroy', $categoria));

        $response
            ->assertRedirect()
            ->assertSessionHas('success', 'Categoria eliminada.');

        $this->assertDatabaseMissing('categorias', [
            'id_categoria' => $categoria->id_categoria,
        ]);
    }

    public function test_admin_cannot_delete_a_category_with_products(): void
    {
        $admin = $this->admin();
        $categoria = Categoria::create(['nombre' => 'Cafe']);

        Producto::create([
            'id_categoria' => $categoria->id_categoria,
            'nombre' => 'Latte',
            'descripcion' => 'Cafe con leche',
            'precio' => 3.50,
            'disponible' => true,
        ]);

        $response = $this
            ->actingAs($admin)
            ->delete(route('admin.categories.destroy', $categoria));

        $response
            ->assertRedirect()
            ->assertSessionHasErrors('categoria');

        $this->assertDatabaseHas('categorias', [
            'id_categoria' => $categoria->id_categoria,
        ]);
    }

    private function admin(): User
    {
        return User::create([
            'nombre' => 'Admin',
            'email' => uniqid('admin', true).'@example.test',
            'contrasena' => 'password123',
            'rol' => 'administrador',
        ]);
    }
}
