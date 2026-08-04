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
public function showRecibo($id_cobro)
{
    $cobroOficial = \App\Models\Cobro::findOrFail($id_cobro);

    $lectura = \App\Models\Lectura::with(['vivienda.propietarios'])
        ->where('id_vivienda', $cobroOficial->id_vivienda)
        ->whereMonth('fecha_lectura', $cobroOficial->periodo_mes)
        ->whereYear('fecha_lectura', $cobroOficial->periodo_anio)
        ->firstOrFail();

    $propietario = $lectura->vivienda->propietarios->first();
    $id_propietario = $propietario->id_usuario ?? 0;

    $monto_wally = \DB::table('reservas')
        ->join('areas_recreativas', 'reservas.id_area', '=', 'areas_recreativas.id_area')
        ->where('reservas.id_usuario', $id_propietario)
        ->whereMonth('reservas.fecha_reserva', $cobroOficial->periodo_mes)
        ->whereYear('reservas.fecha_reserva', $cobroOficial->periodo_anio)
        ->where('areas_recreativas.nombre_area', 'Wally')
        ->sum('reservas.costo_pactado') ?? 0;

    $monto_salon = \DB::table('reservas')
        ->join('areas_recreativas', 'reservas.id_area', '=', 'areas_recreativas.id_area')
        ->where('reservas.id_usuario', $id_propietario)
        ->whereMonth('reservas.fecha_reserva', $cobroOficial->periodo_mes)
        ->whereYear('reservas.fecha_reserva', $cobroOficial->periodo_anio)
        ->where('areas_recreativas.nombre_area', 'Salón de Eventos')
        ->sum('reservas.costo_pactado') ?? 0;

    $monto_consumo  = $cobroOficial->monto_agua;
    $alcantarillado = $cobroOficial->monto_alcantarillado;
    $mantenimiento  = $cobroOficial->monto_mantenimiento;
    $mora           = $cobroOficial->monto_multa;

    $total = $monto_consumo + $alcantarillado + $mantenimiento + $mora + $monto_wally + $monto_salon;

    $lect_ant = $lectura->lectura_anterior;
    $lect_act = $lectura->lectura_actual;
    $consumo_m3 = $lect_act - $lect_ant;

    return view('admin.lecturas.recibo', compact(
        'lectura', 'propietario', 'lect_ant', 'lect_act', 'consumo_m3',
        'monto_consumo', 'alcantarillado', 'mantenimiento', 'mora', 
        'monto_wally', 'monto_salon', 'total'
    ));
}
}
