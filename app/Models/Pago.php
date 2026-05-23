<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pago extends Model
{
    protected $table = 'pagos';
    protected $primaryKey = 'id_pago';
    protected $fillable = ['id_pedido', 'metodo_pago', 'monto', 'estado', 'fecha_pago'];
    protected $casts = ['monto' => 'decimal:2', 'fecha_pago' => 'datetime'];

    public function pedido()
    {
        return $this->belongsTo(Pedido::class, 'id_pedido', 'id_pedido');
    }
}
