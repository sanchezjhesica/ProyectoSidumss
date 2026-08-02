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
     * Muestra el formulario con la lectura anterior para evitar errores del medidor
     */
    public function nuevaLectura() {
        $viviendas = Vivienda::where('estado_vivienda', true)->get();
        
        foreach($viviendas as $v) {
            // Buscamos la última lectura grabada para que el operador la vea como referencia
            $ultima = Lectura::where('id_vivienda', $v->id_vivienda)->latest('id_lectura')->first();
            $v->ultima_lectura = $ultima ? $ultima->lectura_actual : 0;
        }

        return view('operador.lecturas_form', compact('viviendas'));
    }

    /**
     * MÉTODO CON TRANSACCIÓN: Registra consumo y genera deuda con MORA automática
     */
    public function guardarLectura(Request $request) {
        $request->validate([
            'id_vivienda' => 'required|exists:viviendas,id_vivienda',
            'lectura_actual' => 'required|numeric' // Acepta decimales
        ]);

        // 1. INICIAR TRANSACCIÓN (Seguridad total de datos)
        DB::beginTransaction();

        try {
            // A. Cálculo de consumo con decimales
            $ultimaLectura = Lectura::where('id_vivienda', $request->id_vivienda)
                                    ->orderBy('id_lectura', 'desc')->first();
            
            $lecturaAnterior = $ultimaLectura ? $ultimaLectura->lectura_actual : 0;
            $consumoM3 = $request->lectura_actual - $lecturaAnterior;

            if ($consumoM3 < 0) {
                throw new \Exception("La lectura actual (" . number_format($request->lectura_actual, 2) . ") no puede ser menor a la anterior (" . number_format($lecturaAnterior, 2) . ").");
            }

            // B. Obtener tarifas vigentes
            $tarifa = Tarifa::latest('id_tarifa')->first();
            if (!$tarifa) {
                throw new \Exception("No hay tarifas configuradas en el sistema.");
            }

            // C. LÓGICA DE MORA AUTOMÁTICA
            // Contamos si tiene cobros 'Pendientes' de meses anteriores
            $tieneDeudaPendiente = Cobro::where('id_vivienda', $request->id_vivienda)
                                        ->where('estado_pago', 'Pendiente')
                                        ->exists();

            // D. CÁLCULO DE MONTOS
            $montoAgua = $consumoM3 * $tarifa->precio_por_m3_agua;
            $montoMantenimiento = $tarifa->monto_fijo_mantenimiento;
            $montoAlcantarillado = $tarifa->monto_alcantarillado;
            
            // Sumar reservas de Wally/Salón del dueño de esta casa (Mes actual)
            $montoReservas = DB::table('reservas')
                ->whereIn('id_usuario', function($query) use ($request) {
                    $query->select('id_usuario')->from('propietario_vivienda')
                          ->where('id_vivienda', $request->id_vivienda);
                })
                ->whereMonth('fecha_reserva', now()->month)
                ->where('estado_pago', 'Pendiente')
                ->sum('costo_pactado');

            // Calcular Subtotal antes de la mora
            $subtotal = $montoAgua + $montoMantenimiento + $montoAlcantarillado + $montoReservas;

            // Aplicar mora del 2% (u otro %) definido en la tabla tarifas si debe meses anteriores
            $montoMora = 0;
            if ($tieneDeudaPendiente) {
                $montoMora = $subtotal * ($tarifa->porcentaje_mora / 100);
            }

            $totalPagar = $subtotal + $montoMora;

            // E. GUARDAR LECTURA
            Lectura::create([
                'id_vivienda' => $request->id_vivienda,
                'id_operador' => Auth::id() ?? 1, 
                'fecha_lectura' => now(),
                'lectura_anterior' => $lecturaAnterior,
                'lectura_actual' => $request->lectura_actual,
            ]);

            // F. GENERAR COBRO OFICIAL
            Cobro::create([
                'id_vivienda' => $request->id_vivienda,
                'id_tarifa'   => $tarifa->id_tarifa,
                'periodo_mes' => now()->month,
                'periodo_anio'=> now()->year,
                'monto_agua'  => $montoAgua,
                'monto_mantenimiento' => $montoMantenimiento,
                'monto_alcantarillado'=> $montoAlcantarillado,
                'monto_multa'         => $montoMora, // Aquí se guarda la mora calculada
                'monto_reservas'      => $montoReservas,
                'total_pagar'         => $totalPagar,
                'estado_pago'         => 'Pendiente',
                'fecha_emision'       => now()
            ]);

            // 2. CONFIRMAR CAMBIOS (Commit)
            DB::commit();

            return redirect()->route('operador.lecturas.crear')->with('success', 'Aviso de cobro registrado correctamente.');

        } catch (\Exception $e) {
            // 3. DESHACER TODO SI HAY ERROR (Rollback)
            DB::rollBack();
            return back()->withErrors(['error' => 'Lectura no registrada: ' . $e->getMessage()]);
        }
    }

    /**
     * Muestra el historial de averías
     */
    public function listaAverias() {
        $viviendas = Vivienda::where('estado_vivienda', true)->get();
        
        $averias = DB::table('reporte_averias')
            ->join('viviendas', 'reporte_averias.id_vivienda', '=', 'viviendas.id_vivienda')
            ->select('reporte_averias.*', 'viviendas.nro_casa')
            ->orderBy('fecha_reporte', 'desc')
            ->get();

        return view('operador.averias', compact('averias', 'viviendas'));
    }

    /**
     * Procesa el reporte de medidor dañado
     */
    public function reportarAveria(Request $request) {
        $request->validate([
            'id_vivienda' => 'required',
            'descripcion' => 'required|string|max:500'
        ]);

        DB::table('reporte_averias')->insert([
            'id_vivienda' => $request->id_vivienda,
            'id_operador' => Auth::id() ?? 1,
            'descripcion_problema' => $request->descripcion,
            'fecha_reporte' => now(),
            'estado_reparacion' => 'Pendiente'
        ]);

        return redirect()->back()->with('success', 'Reporte de daño enviado correctamente.');
    }
}