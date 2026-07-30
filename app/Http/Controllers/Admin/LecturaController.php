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
    public function showRecibo($id)
    {
        // 1. Cargar datos
        $lectura = \App\Models\Lectura::with(['vivienda.propietarios'])->findOrFail($id);
        $propietario = $lectura->vivienda->propietarios->first();
        $tarifas = \DB::table('tarifas')->latest('id_tarifa')->first();

        // 2. Definir lecturas (ESTO ES LO QUE TE DABA ERROR)
        $lect_ant = $lectura->lectura_anterior; 
        $lect_act = $lectura->lectura_actual;   

        // 3. Buscar Wally Dinámico
        $mes = date('m', strtotime($lectura->fecha_lectura));
        $anio = date('Y', strtotime($lectura->fecha_lectura));

        $monto_wally = \DB::table('reservas')
            ->join('areas_recreativas', 'reservas.id_area', '=', 'areas_recreativas.id_area')
            ->where('reservas.id_usuario', $propietario->id_usuario ?? 0) 
            ->whereMonth('reservas.fecha_reserva', $mes)
            ->whereYear('reservas.fecha_reserva', $anio)
            ->where('areas_recreativas.nombre_area', 'Wally')
            ->sum('reservas.costo_pactado');

        // 4. Cálculos finales
        $consumo_m3 = $lect_act - $lect_ant;
        $monto_consumo = $consumo_m3 * ($tarifas->precio_por_m3_agua ?? 5);
        $alcantarillado = $tarifas->monto_alcantarillado ?? 10;
        $mora = 0.00;
        $total = $monto_consumo + $alcantarillado + $mora + $monto_wally;

        // 5. ENVIAR TODO A LA VISTA (Asegúrate de que lect_ant y lect_act estén aquí)
        return view('admin.lecturas.recibo', compact(
            'lectura', 'propietario', 'lect_ant', 'lect_act', 
            'consumo_m3', 'monto_consumo', 'alcantarillado', 
            'monto_wally', 'mora', 'total'
        ));
    }
}