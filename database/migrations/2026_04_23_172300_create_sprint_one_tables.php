<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sucursales', function (Blueprint $table) {
            $table->increments('id_sucursal');
            $table->string('nombre', 100);
            $table->string('direccion', 255);
            $table->string('telefono', 20)->nullable();
            $table->timestamps();
        });

        Schema::create('categorias', function (Blueprint $table) {
            $table->increments('id_categoria');
            $table->string('nombre', 100);
            $table->text('descripcion')->nullable();
            $table->timestamps();
        });

        Schema::create('productos', function (Blueprint $table) {
            $table->increments('id_producto');
            $table->unsignedInteger('id_categoria');
            $table->string('nombre', 100);
            $table->text('descripcion')->nullable();
            $table->text('alergenos')->nullable();
            $table->decimal('precio', 8, 2);
            $table->boolean('disponible')->default(true);
            $table->timestamps();

            $table->foreign('id_categoria')
                ->references('id_categoria')
                ->on('categorias')
                ->restrictOnDelete()
                ->cascadeOnUpdate();
        });

        Schema::create('pedidos', function (Blueprint $table) {
            $table->increments('id_pedido');
            $table->unsignedInteger('id_usuario');
            $table->unsignedInteger('id_sucursal');
            $table->date('fecha');
            $table->time('hora_recogida');
            $table->enum('estado', ['pendiente', 'en_preparacion', 'listo', 'recogido', 'cancelado'])->default('pendiente');
            $table->decimal('total', 8, 2)->default(0);
            $table->timestamps();

            $table->foreign('id_usuario')
                ->references('id_usuario')
                ->on('usuarios')
                ->restrictOnDelete()
                ->cascadeOnUpdate();
            $table->foreign('id_sucursal')
                ->references('id_sucursal')
                ->on('sucursales')
                ->restrictOnDelete()
                ->cascadeOnUpdate();
        });

        Schema::create('detalle_pedido', function (Blueprint $table) {
            $table->increments('id_detalle');
            $table->unsignedInteger('id_pedido');
            $table->unsignedInteger('id_producto');
            $table->unsignedInteger('cantidad')->default(1);
            $table->decimal('precio_unitario', 8, 2);
            $table->text('notas')->nullable();
            $table->timestamps();

            $table->foreign('id_pedido')
                ->references('id_pedido')
                ->on('pedidos')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
            $table->foreign('id_producto')
                ->references('id_producto')
                ->on('productos')
                ->restrictOnDelete()
                ->cascadeOnUpdate();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('detalle_pedido');
        Schema::dropIfExists('pedidos');
        Schema::dropIfExists('productos');
        Schema::dropIfExists('categorias');
        Schema::dropIfExists('sucursales');
    }
};
