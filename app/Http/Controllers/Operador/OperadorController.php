<?php

namespace App\Http\Controllers\Operador;

use App\Http\Controllers\Controller;
use App\Models\Vivienda;
use App\Models\Lectura;
use App\Models\Cobro;     // Importante para la transacción
use App\Models\Tarifa;    // Importante para obtener precios
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class OperadorController extends Controller
{
    public function index() {
        return view('operador.dashboard');
    }

    public function nuevaLectura() {
        $viviendas = Vivienda::where('estado_vivienda', true)->get();
        return view('operador.lecturas_form', compact('viviendas'));
    }

    /**
     * MÉTODO CON TRANSACCIÓN (ACID)
     * Registra el consumo y genera la deuda en un solo paso.
     */
    public function guardarLectura(Request $request) {
        $request->validate([
            'id_vivienda' => 'required|exists:viviendas,id_vivienda',
            'lectura_actual' => 'required|numeric'
        ]);

        // 1. INICIAR TRANSACCIÓN
        // Esto asegura que si falla la creación del cobro, se borre también la lectura.
        DB::beginTransaction();

        try {
            // A. Obtener última lectura para calcular el consumo
            $ultimaLectura = Lectura::where('id_vivienda', $request->id_vivienda)
                                    ->orderBy('id_lectura', 'desc')->first();
            
            $lecturaAnterior = $ultimaLectura ? $ultimaLectura->lectura_actual : 0;
            $consumoM3 = $request->lectura_actual - $lecturaAnterior;

            // Validación de integridad: El medidor no puede retroceder
            if ($consumoM3 < 0) {
                throw new \Exception("La lectura actual no puede ser menor a la anterior ($lecturaAnterior).");
            }

            // B. Obtener las tarifas vigentes del sistema
            $tarifa = Tarifa::latest('id_tarifa')->first();
            if (!$tarifa) {
                throw new \Exception("No se pueden generar cobros: No hay tarifas configuradas.");
            }

            // C. GUARDAR LECTURA
            $nuevaLectura = Lectura::create([
                'id_vivienda' => $request->id_vivienda,
                'id_operador' => Auth::id() ?? 1, // ID del operador logueado
                'fecha_lectura' => now(),
                'lectura_anterior' => $lecturaAnterior,
                'lectura_actual' => $request->lectura_actual,
            ]);

            // D. CÁLCULO DE COBRO DINÁMICO
            $montoAgua = $consumoM3 * $tarifa->precio_por_m3_agua;
            
            // Buscar si el dueño de esta casa tiene reservas de Wally/Salón este mes
            $montoReservas = DB::table('reservas')
                ->whereIn('id_usuario', function($query) use ($request) {
                    $query->select('id_usuario')->from('propietario_vivienda')
                          ->where('id_vivienda', $request->id_vivienda);
                })
                ->whereMonth('fecha_reserva', now()->month)
                ->where('estado_pago', 'Pendiente')
                ->sum('costo_pactado');

            $totalPagar = $montoAgua + $tarifa->monto_fijo_mantenimiento + $tarifa->monto_alcantarillado + $montoReservas;

            // E. GENERAR COBRO (DEUDA)
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
            // Si llegamos aquí, todo salió bien. Guardamos físicamente en la DB.
            DB::commit();

            return redirect()->route('operador.dashboard')->with('success', 'Lectura y Cobro generados con éxito.');

        } catch (\Exception $e) {
            // 3. DESHACER TODO (Rollback)
            // Si hubo un error en cualquier paso de arriba, la base de datos vuelve a como estaba.
            DB::rollBack();
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function reportarAveria(Request $request) {
        DB::table('reporte_averias')->insert([
            'id_vivienda' => $request->id_vivienda,
            'id_operador' => Auth::id() ?? 1,
            'descripcion_problema' => $request->descripcion,
            'fecha_reporte' => now()
        ]);
        return redirect()->back()->with('success', 'Reporte de daño enviado.');
    }
}