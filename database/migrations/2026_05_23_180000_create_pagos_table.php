<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pagos', function (Blueprint $table) {
            $table->increments('id_pago');
            $table->unsignedInteger('id_pedido')->unique();
            $table->enum('metodo_pago', ['tarjeta', 'efectivo', 'paypal', 'bizum']);
            $table->decimal('monto', 8, 2);
            $table->enum('estado', ['pendiente', 'pagado', 'rechazado', 'cancelado'])->default('pendiente');
            $table->dateTime('fecha_pago')->nullable();
            $table->timestamps();

            $table->foreign('id_pedido')
                ->references('id_pedido')
                ->on('pedidos')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pagos');
    }
};
