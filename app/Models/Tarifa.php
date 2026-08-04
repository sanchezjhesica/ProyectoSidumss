<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tarifa extends Model
{
    protected $table = 'tarifas';
    protected $primaryKey = 'id_tarifa';
    public $timestamps = false;
    protected $fillable = [
        'monto_fijo_mantenimiento', 
        'precio_por_m3_agua', 
        'monto_alcantarillado', 
        'porcentaje_mora'];
}