<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vivienda extends Model
{
    protected $table = 'viviendas';
    protected $primaryKey = 'id_vivienda';
    public $timestamps = false;
    
    protected $fillable = [
        'nro_casa', 
        'nro_lote', 
        'calle', 
        'tipo_vivienda', 
        'nro_medidor', 
        'estado_vivienda'
    ];
    public function propietarios()
    {
        return $this->belongsToMany(User::class, 'propietario_vivienda', 'id_vivienda', 'id_usuario');
    }
    public function cobros()
    {
        return $this->hasMany(Cobro::class, 'id_vivienda', 'id_vivienda');
    }
}