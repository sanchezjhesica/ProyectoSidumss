<?php

namespace App\Http\Controllers\Operador;

use App\Http\Controllers\Controller;
use App\Models\Vivienda;
use App\Models\Lectura;
use App\Models\Cobro;     
use App\Models\Tarifa;    
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class OperadorController extends Controller
{
    public function index() {
        return view('operador.dashboard');
    }

    /**
     * Muestra el formulario de captura con la lectura anterior precargada
     */
    public function nuevaLectura() {
        $viviendas = Vivienda::where('estado_vivienda', true)->get();
        
        foreach($viviendas as $v) {
            $ultima = Lectura::where('id_vivienda', $v->id_vivienda)->latest('id_lectura')->first();
            $v->ultima_lectura = $ultima ? $ultima->lectura_actual : 0;
        }

        return view('operador.lecturas_form', compact('viviendas'));
    }

    /**
     * MÉTODO CON TRANSACCIÓN (ACID)
     */
    public function guardarLectura(Request $request) {
        $request->validate([
            'id_vivienda' => 'required|exists:viviendas,id_vivienda',
            'lectura_actual' => 'required|numeric'
        ]);

        // 1. INICIAR TRANSACCIÓN
        DB::beginTransaction();

        try {
            // A. Obtener última lectura para calcular el consumo
            $ultimaLectura = Lectura::where('id_vivienda', $request->id_vivienda)
                                    ->orderBy('id_lectura', 'desc')->first();
            
            $lecturaAnterior = $ultimaLectura ? $ultimaLectura->lectura_actual : 0;
            $consumoM3 = $request->lectura_actual - $lecturaAnterior;

            // Validación de integridad
            if ($consumoM3 < 0) {
                throw new \Exception("La lectura actual no puede ser menor a la anterior ($lecturaAnterior).");
            }

            // B. Obtener las tarifas vigentes
            $tarifa = Tarifa::latest('id_tarifa')->first();
            if (!$tarifa) {
                throw new \Exception("No hay tarifas configuradas en el sistema.");
            }

            // C. GUARDAR LECTURA
            Lectura::create([
                'id_vivienda' => $request->id_vivienda,
                'id_operador' => Auth::id() ?? 1, 
                'fecha_lectura' => now(),
                'lectura_anterior' => $lecturaAnterior,
                'lectura_actual' => $request->lectura_actual,
            ]);

            // D. CÁLCULO DE COBRO DINÁMICO
            $montoAgua = $consumoM3 * $tarifa->precio_por_m3_agua;
            
            // Buscar reservas de Wally/Salón (id_rol 3 es Propietario)
            $montoReservas = DB::table('reservas')
                ->whereIn('id_usuario', function($query) use ($request) {
                    $query->select('id_usuario')->from('propietario_vivienda')
                          ->where('id_vivienda', $request->id_vivienda);
                })
                ->whereMonth('fecha_reserva', now()->month)
                ->where('estado_pago', 'Pendiente')
                ->sum('costo_pactado');

            $totalPagar = $montoAgua + $tarifa->monto_fijo_mantenimiento + $tarifa->monto_alcantarillado + $montoReservas;

            // E. GENERAR COBRO
            Cobro::create([
                'id_vivienda' => $request->id_vivienda,
                'id_tarifa'   => $tarifa->id_tarifa,
                'periodo_mes' => now()->month,
                'periodo_anio'=> now()->year,
                'monto_agua'  => $montoAgua,
                'monto_mantenimiento' => $tarifa->monto_fijo_mantenimiento,
                'monto_alcantarillado'=> $tarifa->monto_alcantarillado,
                'monto_reservas'      => $montoReservas,
                'total_pagar'         => $totalPagar,
                'estado_pago'         => 'Pendiente',
                'fecha_emision'       => now()
            ]);

            // 2. CONFIRMAR CAMBIOS
            DB::commit();

            // MENSAJE DE ÉXITO SOLICITADO
            return redirect()->route('operador.lecturas.crear')->with('success', 'Aviso de cobro registrado correctamente.');

        } catch (\Exception $e) {
            // 3. DESHACER TODO (Rollback)
            DB::rollBack();

            // MENSAJE DE ERROR SOLICITADO
            return back()->withErrors(['error' => 'Lectura no registrada: ' . $e->getMessage()]);
        }
    }

    /**
     * Reporte de averías técnicas
     */
// Función para mostrar la lista de averías (la que daba error)
public function listaAverias() 
{
    $viviendas = Vivienda::where('estado_vivienda', true)->get();
    
    // Obtenemos las averías reportadas uniendo con la tabla viviendas para ver el número de casa
    $averias = DB::table('reporte_averias')
        ->join('viviendas', 'reporte_averias.id_vivienda', '=', 'viviendas.id_vivienda')
        ->select('reporte_averias.*', 'viviendas.nro_casa')
        ->orderBy('fecha_reporte', 'desc')
        ->get();

    return view('operador.averias', compact('averias', 'viviendas'));
}

    // Función para procesar el reporte enviado desde el formulario
    public function reportarAveria(Request $request) 
    {
        $request->validate([
            'id_vivienda' => 'required',
            'descripcion' => 'required|string'
        ]);

        DB::table('reporte_averias')->insert([
            'id_vivienda' => $request->id_vivienda,
            'id_operador' => Auth::id() ?? 1, // Usa el ID del operador logueado
            'descripcion_problema' => $request->descripcion,
            'fecha_reporte' => now(),
            'estado_reparacion' => 'Pendiente'
        ]);

        return redirect()->back()->with('success', 'Reporte de avería enviado correctamente.');
    }
}