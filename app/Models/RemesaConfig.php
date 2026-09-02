<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RemesaConfig extends Model {
    protected $table = 'remesas_config';
    protected $primaryKey = 'id_remesa_config';
    public $timestamps = false;
    protected $fillable = ['nombre_remesa', 'monto_estandar', 'descripcion'];
}