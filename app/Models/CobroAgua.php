<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CobroAgua extends Model {
    protected $table = 'cobros_agua';
    protected $primaryKey = 'id_cobro_agua';
    public $timestamps = false;
    protected $fillable = [
        'id_vivienda', 
    'id_lectura', 
    'mes', 
    'anio', 
    'subtotal_consumo', 
    'monto_alcantarillado', 
    'monto_mora', 
    'total_pagar',
    'estado_pago', 
    'fecha_pago'];
     
     public function vivienda()
    {
        return $this->belongsTo(Vivienda::class, 'id_vivienda', 'id_vivienda');
    }
     public function lectura()
    {
        return $this->belongsTo(Lectura::class, 'id_lectura', 'id_lectura');
    }
}