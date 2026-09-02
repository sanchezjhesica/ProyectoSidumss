<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Vivienda;
use App\Models\User;
use App\Models\Egreso;
use App\Models\Lectura;
use App\Models\CobroAgua;
use App\Models\CobroMantenimiento;
use App\Models\CobroRemesa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class ReporteController extends Controller
{
    /**
     * Reporte General (Vista Web)
     */
    public function index()
    {
        // 1. Cálculos de INGRESOS (Usando total_remesa)
        $ingAgua = CobroAgua::where('estado_pago', 'Pagado')->sum('total_pagar');
        $ingMante = CobroMantenimiento::where('estado_pago', 'Pagado')->sum('monto_fijo');
        $ingRemesas = CobroRemesa::where('estado_pago', 'Pagado')->sum('total_remesa');
        
        $totalIngresos = $ingAgua + $ingMante + $ingRemesas;
        $totalEgresos = Egreso::sum('monto');
        $saldoCaja = $totalIngresos - $totalEgresos;
        
        $totalPendiente = CobroAgua::where('estado_pago', 'Pendiente')->sum('total_pagar');
        $totalUsuarios = User::where('id_rol', 3)->count();

        // 2. Unificar ingresos para la tabla
        $detallesAgua = CobroAgua::with('vivienda')->where('estado_pago', 'Pagado')->get()->map(function($i){
            return (object)[ 'fecha' => $i->fecha_pago, 'casa' => $i->vivienda->nro_casa, 'concepto' => 'Agua '.$i->mes.'/'.$i->anio, 'monto' => $i->total_pagar ];
        });

        $detallesMante = CobroMantenimiento::with('vivienda')->where('estado_pago', 'Pagado')->get()->map(function($i){
            return (object)[ 'fecha' => $i->fecha_pago, 'casa' => $i->vivienda->nro_casa, 'concepto' => 'Mante. '.$i->mes.'/'.$i->anio, 'monto' => $i->monto_fijo ];
        });

        $detallesRemesas = CobroRemesa::with('vivienda')->where('estado_pago', 'Pagado')->get()->map(function($i){
            return (object)[ 'fecha' => $i->fecha_pago, 'casa' => $i->vivienda->nro_casa, 'concepto' => 'Remesa '.$i->mes.'/'.$i->anio, 'monto' => $i->total_remesa ];
        });

        $listaIngresos = $detallesAgua->concat($detallesMante)->concat($detallesRemesas)->sortByDesc('fecha')->take(20);
        $listaEgresos = Egreso::orderBy('fecha_egreso', 'desc')->take(20)->get();

        return view('admin.reportes.index', compact('totalIngresos', 'totalEgresos', 'saldoCaja', 'totalPendiente', 'totalUsuarios', 'listaIngresos', 'listaEgresos'));
    }

    /**
     * Descargar Reporte General en PDF
     */
    public function descargarGeneral()
    {
        $ingAgua = CobroAgua::where('estado_pago', 'Pagado')->sum('total_pagar');
        $ingMante = CobroMantenimiento::where('estado_pago', 'Pagado')->sum('monto_fijo');
        $ingRemesas = CobroRemesa::where('estado_pago', 'Pagado')->sum('total_remesa');
        
        $totalIngresos = $ingAgua + $ingMante + $ingRemesas;
        $totalEgresos = Egreso::sum('monto');
        $saldoCaja = $totalIngresos - $totalEgresos;

        $detallesAgua = CobroAgua::with('vivienda')->where('estado_pago', 'Pagado')->get()->map(function($i){
            return (object)[ 'fecha' => $i->fecha_pago, 'casa' => $i->vivienda->nro_casa, 'concepto' => 'Agua '.$i->mes.'/'.$i->anio, 'monto' => $i->total_pagar ];
        });
        $detallesMante = CobroMantenimiento::with('vivienda')->where('estado_pago', 'Pagado')->get()->map(function($i){
            return (object)[ 'fecha' => $i->fecha_pago, 'casa' => $i->vivienda->nro_casa, 'concepto' => 'Mante. '.$i->mes.'/'.$i->anio, 'monto' => $i->monto_fijo ];
        });
        $detallesRemesas = CobroRemesa::with('vivienda')->where('estado_pago', 'Pagado')->get()->map(function($i){
            return (object)[ 'fecha' => $i->fecha_pago, 'casa' => $i->vivienda->nro_casa, 'concepto' => 'Remesa '.$i->mes.'/'.$i->anio, 'monto' => $i->total_remesa ];
        });

        $listaIngresos = $detallesAgua->concat($detallesMante)->concat($detallesRemesas)->sortByDesc('fecha')->take(50);
        $listaEgresos = Egreso::orderBy('fecha_egreso', 'desc')->take(50)->get();

        $pdf = Pdf::loadView('admin.reportes.general_pdf', compact('totalIngresos', 'totalEgresos', 'saldoCaja', 'listaIngresos', 'listaEgresos'));
        return $pdf->setPaper('letter', 'portrait')->download('Reporte_General_SIDUMSS.pdf');
    }

    /**
     * Reporte de Morosidad
     */
    public function morosidad()
    {
        $morosos = Vivienda::with('propietario')
            ->whereHas('cobrosAgua', function($q) { $q->where('estado_pago', 'Pendiente'); })
            ->orWhereHas('cobrosMantenimiento', function($q) { $q->where('estado_pago', 'Pendiente'); })
            ->orWhereHas('cobrosRemesas', function($q) { $q->where('estado_pago', 'Pendiente'); })
            ->get();

        foreach ($morosos as $v) {
            $dA = CobroAgua::where('id_vivienda', $v->id_vivienda)->where('estado_pago', 'Pendiente')->sum('total_pagar');
            $dM = CobroMantenimiento::where('id_vivienda', $v->id_vivienda)->where('estado_pago', 'Pendiente')->sum('monto_fijo');
            $dR = CobroRemesa::where('id_vivienda', $v->id_vivienda)->where('estado_pago', 'Pendiente')->sum('total_remesa');
            
            $v->total_deuda = $dA + $dM + $dR;
            $v->cantidad_avisos = CobroAgua::where('id_vivienda', $v->id_vivienda)->where('estado_pago', 'Pendiente')->count() +
                                 CobroMantenimiento::where('id_vivienda', $v->id_vivienda)->where('estado_pago', 'Pendiente')->count() +
                                 CobroRemesa::where('id_vivienda', $v->id_vivienda)->where('estado_pago', 'Pendiente')->count();
        }

        return view('admin.reportes.morosidad', compact('morosos'));
    }

    /**
     * Reporte por Vivienda Individual
     */
    public function porVivienda(Request $request)
    {
        $viviendas = Vivienda::with('propietario')->get();
        $viviendaSeleccionada = null;
        $pagosAgua = []; $pagosMante = []; $pagosRemesas = []; $historialLecturas = [];

        if ($request->has('id_vivienda')) {
            $id = $request->id_vivienda;
            $viviendaSeleccionada = Vivienda::with('propietario')->findOrFail($id);
            $pagosAgua = CobroAgua::where('id_vivienda', $id)->orderBy('anio', 'desc')->orderBy('mes', 'desc')->get();
            $pagosMante = CobroMantenimiento::where('id_vivienda', $id)->orderBy('anio', 'desc')->orderBy('mes', 'desc')->get();
            // CORREGIDO: Se quitó 'with(configuracion)' porque la tabla ya no existe
            $pagosRemesas = CobroRemesa::where('id_vivienda', $id)->orderBy('anio', 'desc')->orderBy('mes', 'desc')->get();
            $historialLecturas = Lectura::where('id_vivienda', $id)->orderBy('id_lectura', 'desc')->get();
        }

        return view('admin.reportes.vivienda', compact('viviendas', 'viviendaSeleccionada', 'pagosAgua', 'pagosMante', 'pagosRemesas', 'historialLecturas'));
    }
}