<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Egreso; // <--- NO OLVIDES IMPORTAR EL MODELO
use Illuminate\Http\Request;

class EgresoController extends Controller
{
    public function index()
    {
        $egresos = Egreso::all();
        return view('admin.egresos.index', compact('egresos'));
    }

    public function create()
    {
        return view('admin.egresos.create');
    }

public function store(Request $request)
{
    $request->validate([
        'descripcion' => 'required',
        'monto' => 'required|numeric',
        'categoria' => 'required',
        'fecha_pago' => 'required|date',
    ]);

    \App\Models\Egreso::create($request->all());

    // Redirigir al dashboard con mensaje de éxito
    return redirect()->route('admin.dashboard')->with('success', 'Gasto registrado correctamente.');
}
}