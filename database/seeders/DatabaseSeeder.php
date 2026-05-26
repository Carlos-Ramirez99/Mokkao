<?php

namespace Database\Seeders;

use App\Models\Categoria;
use App\Models\Cupon;
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

        $cafes = Categoria::updateOrCreate(
            ['nombre' => 'Bebida caliente'],
            ['descripcion' => 'Cafes y bebidas calientes preparadas al momento.']
        );

        $frias = Categoria::updateOrCreate(
            ['nombre' => 'Bebida fria'],
            ['descripcion' => 'Opciones refrescantes para llevar.']
        );

        $bolleria = Categoria::updateOrCreate(
            ['nombre' => 'Reposteria'],
            ['descripcion' => 'Acompanamientos dulces.']
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
        Producto::updateOrCreate(
            ['nombre' => 'Cappuccino'],
            ['id_categoria' => $cafes->id_categoria, 'descripcion' => 'Espresso con espuma de leche cremosa y cacao.', 'alergenos' => 'Leche', 'precio' => 3.10, 'disponible' => true]
        );

        Producto::updateOrCreate(
            ['nombre' => 'Americano'],
            ['id_categoria' => $cafes->id_categoria, 'descripcion' => 'Cafe suave y largo preparado con espresso y agua caliente.', 'alergenos' => null, 'precio' => 2.30, 'disponible' => true]
        );

        Producto::updateOrCreate(
            ['nombre' => 'Mokkao Especial'],
            ['id_categoria' => $cafes->id_categoria, 'descripcion' => 'Cafe con chocolate, leche vaporizada y un toque de vainilla.', 'alergenos' => 'Leche', 'precio' => 3.80, 'disponible' => true]
        );

        Producto::updateOrCreate(
            ['nombre' => 'Te Matcha'],
            ['id_categoria' => $frias->id_categoria, 'descripcion' => 'Matcha suave con leche, servido frio o caliente.', 'alergenos' => 'Leche', 'precio' => 3.50, 'disponible' => true]
        );

        Producto::updateOrCreate(
            ['nombre' => 'Iced Latte'],
            ['id_categoria' => $frias->id_categoria, 'descripcion' => 'Espresso con leche fria y hielo, perfecto para llevar.', 'alergenos' => 'Leche', 'precio' => 3.30, 'disponible' => true]
        );

        Producto::updateOrCreate(
            ['nombre' => 'Frappe de Caramelo'],
            ['id_categoria' => $frias->id_categoria, 'descripcion' => 'Bebida fria batida con cafe, leche y caramelo.', 'alergenos' => 'Leche', 'precio' => 4.20, 'disponible' => true]
        );

        Producto::updateOrCreate(
            ['nombre' => 'Muffin de Chocolate'],
            ['id_categoria' => $bolleria->id_categoria, 'descripcion' => 'Muffin esponjoso con pepitas de chocolate.', 'alergenos' => 'Gluten, huevo, leche', 'precio' => 2.60, 'disponible' => true]
        );

        Producto::updateOrCreate(
            ['nombre' => 'Cookie de Avena'],
            ['id_categoria' => $bolleria->id_categoria, 'descripcion' => 'Galleta de avena con textura crujiente y toque de canela.', 'alergenos' => 'Gluten', 'precio' => 1.90, 'disponible' => true]
        );

        Cupon::updateOrCreate(
            ['codigo' => 'MOKKAO10'],
            ['tipo' => 'porcentaje', 'valor' => 10, 'activo' => true]
        );
    }
}
