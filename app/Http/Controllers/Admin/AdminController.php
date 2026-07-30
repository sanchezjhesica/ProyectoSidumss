<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller; // Añade esta línea
use App\Models\User;
use App\Models\Vivienda;
use App\Models\Cobro;
use App\Models\Egreso;

class AdminController extends Controller // ESTA LÍNEA ES LA QUE FALTABA
{
   public function dashboard()
{
    // ... (tus conteos de usuarios y viviendas)
    $totalUsuarios = User::count();
    $totalViviendas = Vivienda::count();

    // Lógica Financiera
    $ingresos = Cobro::where('estado_pago', 'Pagado')->sum('total_pagar');
    $egresos = Egreso::sum('monto');
    $saldo = $ingresos - $egresos;

    // Cobros pendientes (ya lo tienes)
    $deudasPendientes = Cobro::with('vivienda')
        ->where('estado_pago', 'Pendiente')
        ->orderBy('fecha_emision', 'desc')
        ->take(5)->get();

    // NUEVO: Últimos 5 gastos realizados
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
    $cobro->nro_comprobante = 'REC-' . time(); // Genera un número de recibo simple
    $cobro->save();

    return redirect()->route('admin.dashboard')->with('success', 'Pago registrado correctamente.');
}
} // CIERRE DE LA CLASE