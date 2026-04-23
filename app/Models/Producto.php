<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    protected $table = 'productos';
    protected $primaryKey = 'id_producto';
    protected $fillable = ['id_categoria', 'nombre', 'descripcion', 'alergenos', 'precio', 'disponible'];
    protected $casts = ['disponible' => 'boolean', 'precio' => 'decimal:2'];

    public function categoria()
    {
        return $this->belongsTo(Categoria::class, 'id_categoria', 'id_categoria');
    }
}
