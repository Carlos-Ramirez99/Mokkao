<?php

namespace Database\Seeders;

use App\Models\Categoria;
use App\Models\Producto;
use App\Models\Sucursal;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@mokkao.test'],
            [
                'nombre' => 'Administración Mokkao',
                'contrasena' => 'admin12345',
                'telefono' => '910000000',
                'rol' => 'administrador',
            ]
        );

        $sucursal = Sucursal::firstOrCreate(
            ['nombre' => 'Mokkao Centro'],
            ['direccion' => 'Calle Mayor 10, Madrid', 'telefono' => '910123456']
        );

        $cafes = Categoria::firstOrCreate(
            ['nombre' => 'Cafés'],
            ['descripcion' => 'Clásicos preparados al momento.']
        );

        $frias = Categoria::firstOrCreate(
            ['nombre' => 'Bebidas frías'],
            ['descripcion' => 'Opciones refrescantes para llevar.']
        );

        $bolleria = Categoria::firstOrCreate(
            ['nombre' => 'Bollería'],
            ['descripcion' => 'Acompañamientos dulces.']
        );

        Producto::updateOrCreate(
            ['nombre' => 'Espresso'],
            ['id_categoria' => $cafes->id_categoria, 'descripcion' => 'Café intenso de extracción corta.', 'alergenos' => null, 'precio' => 1.80, 'disponible' => true]
        );

        Producto::updateOrCreate(
            ['nombre' => 'Latte'],
            ['id_categoria' => $cafes->id_categoria, 'descripcion' => 'Espresso con leche vaporizada.', 'alergenos' => 'Leche', 'precio' => 2.90, 'disponible' => true]
        );

        Producto::updateOrCreate(
            ['nombre' => 'Cold Brew'],
            ['id_categoria' => $frias->id_categoria, 'descripcion' => 'Café infusionado en frío durante 12 horas.', 'alergenos' => null, 'precio' => 3.40, 'disponible' => true]
        );

        Producto::updateOrCreate(
            ['nombre' => 'Croissant'],
            ['id_categoria' => $bolleria->id_categoria, 'descripcion' => 'Croissant de mantequilla recién horneado.', 'alergenos' => 'Gluten, leche', 'precio' => 2.20, 'disponible' => true]
        );
    }
}
