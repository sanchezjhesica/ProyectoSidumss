<?php

namespace App\Http\Controllers\Operador;

use App\Http\Controllers\Controller;
use App\Models\Vivienda;
use App\Models\Lectura;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OperadorController extends Controller
{
    public function index() {
        return view('operador.dashboard');
    }

    public function nuevaLectura() {
        $viviendas = Vivienda::all();
        return view('operador.lecturas_form', compact('viviendas'));
    }

    public function guardarLectura(Request $request) {
        $request->validate([
            'id_vivienda' => 'required',
            'lectura_actual' => 'required|numeric'
        ]);

        // Buscamos la última lectura de esta casa para saber la anterior
        $ultimaLectura = Lectura::where('id_vivienda', $request->id_vivienda)
                                ->orderBy('id_lectura', 'desc')->first();
        
        $lecturaAnterior = $ultimaLectura ? $ultimaLectura->lectura_actual : 0;

        Lectura::create([
            'id_vivienda' => $request->id_vivienda,
            'fecha_lectura' => now(),
            'lectura_anterior' => $lecturaAnterior,
            'lectura_actual' => $request->lectura_actual,
            'id_operador' => 1 // Aquí iría Auth::id()
        ]);

        return redirect()->route('operador.dashboard')->with('success', 'Lectura registrada.');
    }

    public function reportarAveria(Request $request) {
        DB::table('reporte_averias')->insert([
            'id_vivienda' => $request->id_vivienda,
            'id_operador' => 1,
            'descripcion_problema' => $request->descripcion,
            'fecha_reporte' => now()
        ]);
        return redirect()->back()->with('success', 'Reporte de daño enviado.');
    }
}