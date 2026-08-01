<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lectura;
use App\Models\Vivienda;
use Illuminate\Http\Request;

class LecturaController extends Controller
{
    public function index()
    {
        $lecturas = \App\Models\Lectura::with(['vivienda.cobros'])->get();

        return view('admin.lecturas.index', compact('lecturas'));
    }

    public function create()
    {
        $viviendas = Vivienda::where('estado_vivienda', true)->get();
        return view('admin.lecturas.create', compact('viviendas'));
    }
public function showRecibo($id_cobro) // Ahora recibe el ID del COBRO
{
    // 1. Buscamos el COBRO exacto que el usuario presionó
    $cobroOficial = \App\Models\Cobro::findOrFail($id_cobro);

    // 2. Buscamos la LECTURA que corresponde a esa vivienda y ese mismo periodo
    $lectura = \App\Models\Lectura::with(['vivienda.propietarios'])
        ->where('id_vivienda', $cobroOficial->id_vivienda)
        ->whereMonth('fecha_lectura', $cobroOficial->periodo_mes)
        ->whereYear('fecha_lectura', $cobroOficial->periodo_anio)
        ->firstOrFail();

    $propietario = $lectura->vivienda->propietarios->first();

    // 3. ASIGNAMOS LOS VALORES DEL COBRO (Sin recalcular nada)
    $monto_consumo  = $cobroOficial->monto_agua;
    $alcantarillado = $cobroOficial->monto_alcantarillado;
    $mora           = $cobroOficial->monto_multa;
    $monto_wally    = $cobroOficial->monto_reservas; // El cobro ya sabe cuánto de reservas tiene
    
    // Si quieres separar Wally de Salón, podrías usar una lógica extra, 
    // pero por ahora usemos el monto_reservas que ya está en el cobro.
    $monto_salon    = 0; 
    if($monto_wally > 100) { // Un ejemplo: si es mucho, asumimos que es salón
        $monto_salon = $monto_wally;
        $monto_wally = 0;
    }

    $total = $cobroOficial->total_pagar;

    $lect_ant = $lectura->lectura_anterior;
    $lect_act = $lectura->lectura_actual;
    $consumo_m3 = $lect_act - $lect_ant;

    return view('admin.lecturas.recibo', compact(
        'lectura', 'propietario', 'lect_ant', 'lect_act', 
        'consumo_m3', 'monto_consumo', 'alcantarillado', 
        'mora', 'monto_wally', 'monto_salon', 'total'
    ));
}
}
