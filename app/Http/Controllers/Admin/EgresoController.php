<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Egreso;
use Illuminate\Http\Request;

class EgresoController extends Controller
{
    public function index()
    {
        $egresos = Egreso::with('administrador')->orderBy('fecha_egreso', 'desc')->get();
        return view('admin.egresos.index', compact('egresos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'descripcion' => 'required|string',
            'monto' => 'required|numeric',
            'categoria' => 'required',
            'fecha_egreso' => 'required|date',
        ]);


        Egreso::create([
            'descripcion' => $request->descripcion,
            'monto' => $request->monto,
            'categoria' => $request->categoria,
            'fecha_egreso' => $request->fecha_egreso,
            'id_admin' => auth()->id(), 
        ]);

        return redirect()->route('admin.dashboard')->with('success', 'Gasto registrado correctamente');
    }

    public function destroy($id)
    {
        $egreso = Egreso::findOrFail($id);
        $egreso->delete();
    
        return back()->with('success', 'Registro de gasto eliminado.');
    }
}