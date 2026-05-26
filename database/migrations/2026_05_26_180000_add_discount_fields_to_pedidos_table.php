<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pedidos', function (Blueprint $table) {
            $table->string('codigo_descuento', 50)->nullable()->after('estado');
            $table->decimal('descuento', 8, 2)->default(0)->after('codigo_descuento');
        });
    }

    public function down(): void
    {
        Schema::table('pedidos', function (Blueprint $table) {
            $table->dropColumn(['codigo_descuento', 'descuento']);
        });
    }
};
