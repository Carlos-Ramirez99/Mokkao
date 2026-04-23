<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sucursal extends Model
{
    protected $table = 'sucursales';
    protected $primaryKey = 'id_sucursal';
    protected $fillable = ['nombre', 'direccion', 'telefono'];

    public function pedidos()
    {
        return $this->hasMany(Pedido::class, 'id_sucursal', 'id_sucursal');
    }
}
