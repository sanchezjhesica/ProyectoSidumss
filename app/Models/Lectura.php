<?php

namespace App\Models; // <--- Fíjate que ahora dice Models

use Illuminate\Database\Eloquent\Model;

class Lectura extends Model
{
    protected $table = 'lecturas';
    protected $primaryKey = 'id_lectura';
    public $timestamps = false;

    protected $fillable = [
        'id_vivienda', 
        'fecha_lectura', 
        'lectura_anterior', 
        'lectura_actual', 
        'id_operador'
    ];

    public function vivienda()
    {
        return $this->belongsTo(Vivienda::class, 'id_vivienda', 'id_vivienda');
    }

    public function operador()
    {
        return $this->belongsTo(User::class, 'id_operador', 'id_usuario');
    }
    // Añade esto dentro de la clase Lectura
public function cobro()
{
    // Relacionamos por vivienda, mes y año de la lectura
    return $this->hasOne(Cobro::class, 'id_vivienda', 'id_vivienda')
                ->whereMonth('fecha_emision', \Carbon\Carbon::parse($this->fecha_lectura)->month)
                ->whereYear('fecha_emision', \Carbon\Carbon::parse($this->fecha_lectura)->year);
}
}