<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Vivienda;
use Illuminate\Http\Request;

class ViviendaController extends Controller
{
    public function index() {
        $viviendas = Vivienda::where('estado_vivienda', true)->get();
        return view('admin.viviendas.index', compact('viviendas'));
    }

    public function create() {
        return view('admin.viviendas.create');
    }

    public function store(Request $request) {
        $request->validate([
            'nro_casa' => 'required|string|max:10',
            'nro_medidor' => 'required|string|unique:viviendas,nro_medidor',
            'tipo_vivienda' => 'required'
        ]);

        Vivienda::create($request->all());
        return redirect()->route('admin.viviendas.index')->with('success', 'Vivienda registrada con éxito.');
    }

    public function edit($id) {
        $vivienda = Vivienda::findOrFail($id);
        return view('admin.viviendas.edit', compact('vivienda'));
    }

    public function update(Request $request, $id) {
        $vivienda = Vivienda::findOrFail($id);
        $vivienda->update($request->all());
        return redirect()->route('admin.viviendas.index')->with('success', 'Vivienda actualizada.');
    }

    public function destroy($id) {
        $vivienda = Vivienda::findOrFail($id);
        $vivienda->estado_vivienda = false;
        $vivienda->save();
        return redirect()->route('admin.viviendas.index')->with('success', 'Vivienda eliminada.');
    }
}