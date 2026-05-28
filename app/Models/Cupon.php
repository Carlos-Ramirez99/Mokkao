<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cupon extends Model
{
    protected $table = 'cupones';
    protected $primaryKey = 'id_cupon';
    protected $fillable = ['codigo', 'tipo', 'valor', 'activo'];
    protected $casts = ['valor' => 'decimal:2', 'activo' => 'boolean'];

    public function pedidos()
    {
        return $this->hasMany(Pedido::class, 'id_cupon', 'id_cupon');
    }
}
