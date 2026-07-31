<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cobro extends Model
{
    protected $table = 'cobros';
    protected $primaryKey = 'id_cobro';
    public $timestamps = false;
    protected $fillable = [
        'id_vivienda', 
        'id_tarifa', 
        'periodo_mes', 
        'periodo_anio', 
        'monto_agua', 
        'monto_mantenimiento', 
        'monto_alcantarillado', 
        'monto_multa', 
        'monto_reservas', 
        'total_pagar', 
        'estado_pago',
        'fecha_emision', 
        'fecha_pago', 
        'nro_comprobante'
    ];
    /**
 * Relación con la Vivienda (Un cobro pertenece a una vivienda)
 */
public function vivienda()
{
    // Relacionamos 'id_vivienda' de la tabla cobros con 'id_vivienda' de la tabla viviendas
    return $this->belongsTo(Vivienda::class, 'id_vivienda', 'id_vivienda');
}
}