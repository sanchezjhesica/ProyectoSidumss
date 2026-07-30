<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Vivienda;
use App\Models\Cobro;
use App\Models\Egreso;
use App\Models\Lectura;
use Illuminate\Http\Request;

class ReporteController extends Controller
{
    public function index()
{
    // Totales (Lo que ya tienes)
    $totalIngresos = Cobro::where('estado_pago', 'Pagado')->sum('total_pagar');
    $totalEgresos = Egreso::sum('monto');
    $deudaPendiente = Cobro::where('estado_pago', 'Pendiente')->sum('total_pagar');
    $consumoTotal = Lectura::all()->sum(function($l) {
        return $l->lectura_actual - $l->lectura_anterior;
    });

    // --- NUEVO: DETALLES PARA LAS TABLAS ---
    
    // Lista de ingresos detallada (Casa, Dueño, Monto, Fecha)
    $detallesIngresos = Cobro::with('vivienda.propietarios')
        ->where('estado_pago', 'Pagado')
        ->orderBy('fecha_pago', 'desc')
        ->get();

    // Lista de egresos detallada (Concepto, Categoría, Monto, Fecha)
    $detallesEgresos = Egreso::orderBy('fecha_pago', 'desc')->get();

    return view('admin.reportes.index', compact(
        'totalIngresos', 'totalEgresos', 'deudaPendiente', 'consumoTotal',
        'detallesIngresos', 'detallesEgresos' // Enviamos los detalles
    ));
}

    public function porVivienda(Request $request)
    {
        $viviendas = Vivienda::all();
        $viviendaSeleccionada = null;
        $historialPagos = [];
        $historialLecturas = [];

        if ($request->has('id_vivienda')) {
            $viviendaSeleccionada = Vivienda::with('propietarios')->findOrFail($request->id_vivienda);
            $historialPagos = Cobro::where('id_vivienda', $request->id_vivienda)->orderBy('periodo_anio', 'desc')->get();
            $historialLecturas = Lectura::where('id_vivienda', $request->id_vivienda)->orderBy('fecha_lectura', 'desc')->get();
        }

        return view('admin.reportes.vivienda', compact('viviendas', 'viviendaSeleccionada', 'historialPagos', 'historialLecturas'));
    }
    public function morosidad()
{
    // Buscamos cobros que no estén pagados, cargando la vivienda y su dueño
    $morosos = Cobro::with('vivienda.propietarios')
        ->where('estado_pago', '!=', 'Pagado')
        ->orderBy('total_pagar', 'desc')
        ->get();

    return view('admin.reportes.morosidad', compact('morosos'));
}
}