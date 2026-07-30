<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Vivienda;
use Illuminate\Http\Request;

class AsignacionController extends Controller
{
    public function index()
    {
        // Traemos los usuarios que tienen al menos una vivienda
        $propietarios = User::has('viviendas')->with('viviendas')->get();
        return view('admin.asignaciones.index', compact('propietarios'));
    }

    public function create()
    {
        // Solo usuarios con rol propietario (id_rol = 3) y viviendas activas
        $usuarios = User::where('id_rol', 3)->where('estado_logico', true)->get();
        $viviendas = Vivienda::where('estado_vivienda', true)->get();
        return view('admin.asignaciones.create', compact('usuarios', 'viviendas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_usuario' => 'required',
            'id_vivienda' => 'required'
        ]);

        $usuario = User::findOrFail($request->id_usuario);
        
        // attach() inserta en la tabla intermedia propietario_vivienda
        // syncWithoutDetaching evita duplicados si ya estaba asignada
        $usuario->viviendas()->syncWithoutDetaching([$request->id_vivienda]);

        return redirect()->route('admin.asignaciones.index')->with('success', 'Vivienda asignada correctamente.');
    }

    public function destroy($id_usuario, $id_vivienda)
    {
        $usuario = User::findOrFail($id_usuario);
        $usuario->viviendas()->detach($id_vivienda); // Elimina la relación
        return redirect()->back()->with('success', 'Asignación removida.');
    }
}
