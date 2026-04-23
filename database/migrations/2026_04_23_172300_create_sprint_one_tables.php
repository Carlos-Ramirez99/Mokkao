<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sucursales', function (Blueprint $table) {
            $table->id('id_sucursal');
            $table->string('nombre', 100);
            $table->string('direccion', 255);
            $table->string('telefono', 20)->nullable();
            $table->timestamps();
        });

        Schema::create('categorias', function (Blueprint $table) {
            $table->id('id_categoria');
            $table->string('nombre', 100);
            $table->text('descripcion')->nullable();
            $table->timestamps();
        });

        Schema::create('productos', function (Blueprint $table) {
            $table->id('id_producto');
            $table->foreignId('id_categoria')->constrained('categorias', 'id_categoria')->restrictOnDelete()->cascadeOnUpdate();
            $table->string('nombre', 100);
            $table->text('descripcion')->nullable();
            $table->text('alergenos')->nullable();
            $table->decimal('precio', 8, 2);
            $table->boolean('disponible')->default(true);
            $table->timestamps();
        });

        Schema::create('pedidos', function (Blueprint $table) {
            $table->id('id_pedido');
            $table->foreignId('id_usuario')->constrained('usuarios', 'id_usuario')->restrictOnDelete()->cascadeOnUpdate();
            $table->foreignId('id_sucursal')->constrained('sucursales', 'id_sucursal')->restrictOnDelete()->cascadeOnUpdate();
            $table->date('fecha');
            $table->time('hora_recogida');
            $table->enum('estado', ['pendiente', 'en_preparacion', 'listo', 'recogido', 'cancelado'])->default('pendiente');
            $table->decimal('total', 8, 2)->default(0);
            $table->timestamps();
        });

        Schema::create('detalle_pedido', function (Blueprint $table) {
            $table->id('id_detalle');
            $table->foreignId('id_pedido')->constrained('pedidos', 'id_pedido')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('id_producto')->constrained('productos', 'id_producto')->restrictOnDelete()->cascadeOnUpdate();
            $table->unsignedInteger('cantidad')->default(1);
            $table->decimal('precio_unitario', 8, 2);
            $table->text('notas')->nullable();
            $table->timestamps();
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
