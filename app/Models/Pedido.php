<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pedido extends Model
{
    protected $table = 'pedidos';
    protected $primaryKey = 'id_pedido';
    protected $fillable = ['id_usuario', 'id_sucursal', 'id_cupon', 'fecha', 'hora_recogida', 'estado', 'codigo_descuento', 'descuento', 'total'];
    protected $casts = ['fecha' => 'date', 'descuento' => 'decimal:2', 'total' => 'decimal:2'];

    public function usuario()
    {
        return $this->belongsTo(User::class, 'id_usuario', 'id_usuario');
    }

    public function sucursal()
    {
        return $this->belongsTo(Sucursal::class, 'id_sucursal', 'id_sucursal');
    }

    public function cupon()
    {
        return $this->belongsTo(Cupon::class, 'id_cupon', 'id_cupon');
    }

    public function detalles()
    {
        return $this->hasMany(DetallePedido::class, 'id_pedido', 'id_pedido');
    }

    public function pago()
    {
        return $this->hasOne(Pago::class, 'id_pedido', 'id_pedido');
    }
}
