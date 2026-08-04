<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Vivienda;
use App\Models\Cobro;
use App\Models\Egreso;

class AdminController extends Controller
{
   public function dashboard()
{
    $totalUsuarios = User::count();
    $totalViviendas = Vivienda::count();

    $ingresos = Cobro::where('estado_pago', 'Pagado')->sum('total_pagar');
    $egresos = Egreso::sum('monto');
    $saldo = $ingresos - $egresos;

    $deudasPendientes = Cobro::with('vivienda')
        ->where('estado_pago', 'Pendiente')
        ->orderBy('fecha_emision', 'desc')
        ->take(5)->get();


    $ultimosEgresos = Egreso::orderBy('fecha_pago', 'desc')->take(5)->get();

    return view('admin.dashboard', compact(
        'totalUsuarios', 'totalViviendas', 'ingresos', 
        'egresos', 'saldo', 'deudasPendientes', 'ultimosEgresos'
    ));
}
public function registrarPago($id)
{
    $cobro = Cobro::findOrFail($id);
    $cobro->estado_pago = 'Pagado';
    $cobro->fecha_pago = now();
    $cobro->nro_comprobante = 'REC-' . time();
    $cobro->save();

    return redirect()->route('admin.dashboard')->with('success', 'Pago registrado correctamente.');
}
}