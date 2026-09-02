<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Vivienda;
use App\Models\User;
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Vivienda;
use App\Models\User;
use Illuminate\Http\Request;

class ViviendaController extends Controller
{
    public function index() {
        // Quitamos el filtro de 'estado_vivienda' porque no existe en la BD
        // Cargamos la relación 'propietario' para saber quién vive ahí
        $viviendas = Vivienda::with('propietario')->get();
        return view('admin.viviendas.index', compact('viviendas'));
    }

    public function create() {
        // Obtenemos solo los usuarios que tienen el Rol de Propietario (id_rol = 3)
        $propietarios = User::where('id_rol', 3)->get();
        return view('admin.viviendas.create', compact('propietarios'));
    }

    public function store(Request $request) {
        $request->validate([
            'nro_casa' => 'required|string|max:10|unique:viviendas,nro_casa',
            'nro_medidor' => 'required|string|unique:viviendas,nro_medidor',
            'tipo_vivienda' => 'required',
            'id_propietario' => 'nullable|exists:usuarios,id_usuario'
        ]);

        Vivienda::create($request->all());
        return redirect()->route('admin.viviendas.index')->with('success', 'Vivienda registrada y asignada con éxito.');
    }

    public function edit($id) {
        $vivienda = Vivienda::findOrFail($id);
        $propietarios = User::where('id_rol', 3)->get();
        return view('admin.viviendas.edit', compact('vivienda', 'propietarios'));
    }

    public function update(Request $request, $id) {
        $vivienda = Vivienda::findOrFail($id);
        
        $request->validate([
            'nro_casa' => 'required|string|max:10|unique:viviendas,nro_casa,'.$id.',id_vivienda',
            'nro_medidor' => 'required|string|unique:viviendas,nro_medidor,'.$id.',id_vivienda',
        ]);

        $vivienda->update($request->all());
        return redirect()->route('admin.viviendas.index')->with('success', 'Datos de la vivienda actualizados.');
    }

    public function destroy($id) {
        $vivienda = Vivienda::findOrFail($id);
        // Como no hay columna 'estado_vivienda', hacemos un borrado físico
        // o puedes añadir la columna a la BD si prefieres borrado lógico.
        $vivienda->delete(); 
        return redirect()->route('admin.viviendas.index')->with('success', 'Vivienda eliminada del sistema.');
    }
}