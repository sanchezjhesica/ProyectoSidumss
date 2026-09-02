<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
class CobroMantenimiento extends Model {
    protected $table = 'cobros_mantenimiento';
    protected $primaryKey = 'id_cobro_mantenimiento';
    public $timestamps = false;
    protected $fillable = ['id_vivienda', 'mes', 'anio', 'monto_fijo', 'estado_pago', 'fecha_pago'];
    public function vivienda()
{
    return $this->belongsTo(Vivienda::class, 'id_vivienda', 'id_vivienda');
}
}