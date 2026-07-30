<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'usuarios';
    protected $primaryKey = 'id_usuario';
    public $timestamps = false;

    protected $fillable = [
        'nombre',
        'apellido_paterno',
        'apellido_materno',
        'ci',
        'telefono',
        'email',
        'password',
        'id_rol',
        'estado_logico',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    // ==========================================================
    // AÑADE ESTO AQUÍ (AL FINAL DE LA CLASE):
    // ==========================================================
    
    /**
     * Relación con la tabla Viviendas (Muchos a Muchos)
     */
    public function viviendas()
    {
        // Se conecta a través de la tabla intermedia 'propietario_vivienda'
        return $this->belongsToMany(Vivienda::class, 'propietario_vivienda', 'id_usuario', 'id_vivienda');
    }

    /**
     * Relación con el Rol (Cada usuario tiene un rol)
     */
    public function rol()
    {
        // Esto asume que tienes un modelo Rol creado
        return $this->belongsTo(Rol::class, 'id_rol', 'id_rol');
    }
} // <--- Esta es la última llave, el código debe ir antes de esta.