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

    // ==========================================================
    // AÑADE ESTE CÓDIGO AQUÍ ABAJO:
    // ==========================================================

    /**
     * Relación con los Propietarios (Muchos a Muchos)
     */
    public function propietarios()
    {
        // Se conecta a través de la tabla intermedia 'propietario_vivienda'
        return $this->belongsToMany(User::class, 'propietario_vivienda', 'id_vivienda', 'id_usuario');
    }
    /**
     * Relación con los Cobros (Una vivienda tiene muchos cobros)
     */
    public function cobros()
    {
        // Relacionamos 'id_vivienda' de la tabla viviendas con 'id_vivienda' de la tabla cobros
        return $this->hasMany(Cobro::class, 'id_vivienda', 'id_vivienda');
    }
}