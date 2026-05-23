<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pagos', function (Blueprint $table) {
            $table->id('id_pago');
            $table->foreignId('id_pedido')->unique()->constrained('pedidos', 'id_pedido')->cascadeOnDelete()->cascadeOnUpdate();
            $table->enum('metodo_pago', ['tarjeta', 'efectivo', 'paypal', 'bizum']);
            $table->decimal('monto', 8, 2);
            $table->enum('estado', ['pendiente', 'pagado', 'rechazado', 'cancelado'])->default('pendiente');
            $table->dateTime('fecha_pago')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pagos');
    }
};
