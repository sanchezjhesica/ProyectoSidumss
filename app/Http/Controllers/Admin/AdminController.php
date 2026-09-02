<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Vivienda;
use App\Models\Egreso;
use App\Models\CobroAgua;         // Importar modelos nuevos
use App\Models\CobroMantenimiento;
use App\Models\CobroRemesa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function dashboard()
{
    // 1. Estadísticas básicas
    $totalUsuarios = User::count();
    $totalViviendas = Vivienda::count();

    // 2. Sumar Ingresos Reales (Todo lo pagado en las 3 tablas)
    $ingresosAgua = CobroAgua::where('estado_pago', 'Pagado')->sum('total_pagar');
    $ingresosMante = CobroMantenimiento::where('estado_pago', 'Pagado')->sum('monto_fijo');
    
    // CAMBIO AQUÍ: Usamos 'total_remesa' en lugar de 'monto_pactado'
    $ingresosRemesas = CobroRemesa::where('estado_pago', 'Pagado')->sum('total_remesa');

    $ingresos = $ingresosAgua + $ingresosMante + $ingresosRemesas;

    // 3. Egresos y Saldo
    $egresos = Egreso::sum('monto');
    $saldo = $ingresos - $egresos;

    // 4. Deudas Pendientes
    $deudasPendientes = CobroAgua::with('vivienda')
        ->where('estado_pago', 'Pendiente')
        ->orderBy('id_cobro_agua', 'desc')
        ->take(5)->get();

    // 5. Últimos Egresos
    $ultimosEgresos = Egreso::orderBy('fecha_egreso', 'desc')->take(5)->get();

    return view('admin.dashboard', compact(
        'totalUsuarios', 'totalViviendas', 'ingresos', 
        'egresos', 'saldo', 'deudasPendientes', 'ultimosEgresos'
    ));
}

    /**
     * Registrar pago de un Aviso de Agua
     */
    public function registrarPagoAgua($id)
    {
        $cobro = CobroAgua::findOrFail($id);
        $cobro->estado_pago = 'Pagado';
        $cobro->fecha_pago = now();
        $cobro->save();

        return redirect()->back()->with('success', 'Pago de Agua registrado correctamente.');
    }

    /**
     * Registrar pago de Mantenimiento
     */
    public function registrarPagoMantenimiento($id)
    {
        $cobro = CobroMantenimiento::findOrFail($id);
        $cobro->estado_pago = 'Pagado';
        $cobro->fecha_pago = now();
        $cobro->save();

        return redirect()->back()->with('success', 'Pago de Mantenimiento registrado.');
    }

/**
 * Registrar el pago de la planilla de remesas completa para una casa y mes específico
 */
public function registrarPagoRemesas(Request $request)
{
    // Validamos que lleguen los datos necesarios
    $request->validate([
        'id_vivienda' => 'required',
        'mes' => 'required',
        'anio' => 'required'
    ]);

    // Buscamos todas las remesas de esa vivienda en ese periodo y las marcamos como pagadas
    \App\Models\CobroRemesa::where('id_vivienda', $request->id_vivienda)
        ->where('mes', $request->mes)
        ->where('anio', $request->anio)
        ->update([
            'estado_pago' => 'Pagado',
            'fecha_pago' => now()
        ]);

    return redirect()->back()->with('success', 'Planilla de remesas cobrada correctamente.');
}
public function imagenesRecibidas() {
    // 1. Obtenemos el QR actual de la tabla de tarifas
    $config = DB::table('configuracion_tarifas')->where('estado', true)->first();

    // 2. Obtenemos los comprobantes subidos por los vecinos
    $comprobantes = DB::table('comprobantes_pago')
        ->join('usuarios', 'comprobantes_pago.id_usuario', '=', 'usuarios.id_usuario')
        ->select('comprobantes_pago.*', 'usuarios.nombre', 'usuarios.apellido_paterno', 'usuarios.ci')
        ->orderBy('fecha_subida', 'desc')
        ->get();

    // 3. Retornamos la vista que creamos (admin.imagenes.QR)
    return view('admin.imagenes.QR', compact('config', 'comprobantes'));
}
public function validarComprobante($id)
{
    // 1. Obtener los datos del comprobante
    $comprobante = DB::table('comprobantes_pago')->where('id_comprobante', $id)->first();

    // 2. Marcar como pagado en la tabla correspondiente (Agua, Mantenimiento o Remesas)
    if ($comprobante->tipo_pago == 'agua') {
        DB::table('cobros_agua')->where('id_cobro_agua', $comprobante->id_referencia_pago)
            ->update(['estado_pago' => 'Pagado', 'fecha_pago' => now()]);
    } 
    elseif ($comprobante->tipo_pago == 'mantenimiento') {
        DB::table('cobros_mantenimiento')->where('id_cobro_mantenimiento', $comprobante->id_referencia_pago)
            ->update(['estado_pago' => 'Pagado', 'fecha_pago' => now()]);
    }
    elseif ($comprobante->tipo_pago == 'remesas') {
        DB::table('cobros_remesas')->where('id_cobro_remesa', $comprobante->id_referencia_pago)
            ->update(['estado_pago' => 'Pagado', 'fecha_pago' => now()]);
    }

    // 3. Actualizar el estado del comprobante
    DB::table('comprobantes_pago')->where('id_comprobante', $id)->update(['estado' => 'Validado']);

    return back()->with('success', 'Pago validado y registrado en el sistema.');
}

public function rechazarComprobante($id)
{
    // Solo marcamos como rechazado (el vecino tendrá que subir otra foto)
    DB::table('comprobantes_pago')->where('id_comprobante', $id)->update(['estado' => 'Rechazado']);
    
    return back()->with('success', 'El comprobante ha sido rechazado.');
}
}