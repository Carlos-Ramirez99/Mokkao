<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pedidos', function (Blueprint $table) {
            $table->unsignedInteger('id_cupon')
                ->nullable()
                ->after('id_sucursal');

            $table->foreign('id_cupon')
                ->references('id_cupon')
                ->on('cupones')
                ->nullOnDelete()
                ->cascadeOnUpdate();
        });
    }

    public function down(): void
    {
        Schema::table('pedidos', function (Blueprint $table) {
            $table->dropForeign(['id_cupon']);
            $table->dropColumn('id_cupon');
        });
    }
};
