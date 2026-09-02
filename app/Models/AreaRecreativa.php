<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AreaRecreativa extends Model
{
    // Nombre exacto de la tabla en tu base de datos
    protected $table = 'areas_recreativas';

    // Llave primaria personalizada
    protected $primaryKey = 'id_area';

    // Desactivamos timestamps porque la tabla no tiene created_at/updated_at
    public $timestamps = false;

    // Campos que permitimos llenar desde el controlador
    protected $fillable = [
        'nombre_area',
        'costo_reserva'
    ];
}