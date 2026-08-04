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
    public function nuevaLectura() {
        $viviendas = Vivienda::where('estado_vivienda', true)->get();
        
        foreach($viviendas as $v) {
            $ultima = Lectura::where('id_vivienda', $v->id_vivienda)->latest('id_lectura')->first();
            $v->ultima_lectura = $ultima ? $ultima->lectura_actual : 0;
        }

        return view('operador.lecturas_form', compact('viviendas'));
    }
    public function guardarLectura(Request $request) {
        $request->validate([
            'id_vivienda' => 'required|exists:viviendas,id_vivienda',
            'lectura_actual' => 'required|numeric'
        ]);

        DB::beginTransaction();

        try {
            $ultimaLectura = Lectura::where('id_vivienda', $request->id_vivienda)
                                    ->orderBy('id_lectura', 'desc')->first();
            
            $lecturaAnterior = $ultimaLectura ? $ultimaLectura->lectura_actual : 0;
            $consumoM3 = $request->lectura_actual - $lecturaAnterior;

            if ($consumoM3 < 0) {
                throw new \Exception("La lectura actual (" . number_format($request->lectura_actual, 2) . ") no puede ser menor a la anterior (" . number_format($lecturaAnterior, 2) . ").");
            }

            $tarifa = Tarifa::latest('id_tarifa')->first();
            if (!$tarifa) {
                throw new \Exception("No hay tarifas configuradas en el sistema.");
            }

            $tieneDeudaPendiente = Cobro::where('id_vivienda', $request->id_vivienda)
                                        ->where('estado_pago', 'Pendiente')
                                        ->exists();
            $montoAgua = $consumoM3 * $tarifa->precio_por_m3_agua;
            $montoMantenimiento = $tarifa->monto_fijo_mantenimiento;
            $montoAlcantarillado = $tarifa->monto_alcantarillado;
            $montoReservas = DB::table('reservas')
                ->whereIn('id_usuario', function($query) use ($request) {
                    $query->select('id_usuario')->from('propietario_vivienda')
                          ->where('id_vivienda', $request->id_vivienda);
                })
                ->whereMonth('fecha_reserva', now()->month)
                ->where('estado_pago', 'Pendiente')
                ->sum('costo_pactado');

            $subtotal = $montoAgua + $montoMantenimiento + $montoAlcantarillado + $montoReservas;

            $montoMora = 0;
            if ($tieneDeudaPendiente) {
                $montoMora = $subtotal * ($tarifa->porcentaje_mora / 100);
            }

            $totalPagar = $subtotal + $montoMora;
            Lectura::create([
                'id_vivienda' => $request->id_vivienda,
                'id_operador' => Auth::id() ?? 1, 
                'fecha_lectura' => now(),
                'lectura_anterior' => $lecturaAnterior,
                'lectura_actual' => $request->lectura_actual,
            ]);
            Cobro::create([
                'id_vivienda' => $request->id_vivienda,
                'id_tarifa'   => $tarifa->id_tarifa,
                'periodo_mes' => now()->month,
                'periodo_anio'=> now()->year,
                'monto_agua'  => $montoAgua,
                'monto_mantenimiento' => $montoMantenimiento,
                'monto_alcantarillado'=> $montoAlcantarillado,
                'monto_multa'         => $montoMora,
                'monto_reservas'      => $montoReservas,
                'total_pagar'         => $totalPagar,
                'estado_pago'         => 'Pendiente',
                'fecha_emision'       => now()
            ]);
            DB::commit();

            return redirect()->route('operador.lecturas.crear')->with('success', 'Aviso de cobro registrado correctamente.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Lectura no registrada: ' . $e->getMessage()]);
        }
    }
}