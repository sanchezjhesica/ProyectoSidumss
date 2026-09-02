<?php

namespace App\Http\Controllers\Operador;

use App\Http\Controllers\Controller;
use App\Models\Vivienda;
use App\Models\Lectura;
use App\Models\CobroAgua;         
use App\Models\CobroMantenimiento; 
use App\Models\CobroRemesa;       
use App\Models\ConfiguracionTarifa; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class OperadorController extends Controller
{
    /**
     * PÁGINA 1: DASHBOARD (Solo muestra el historial)
     */
    public function index() 
    {
        // Obtenemos el historial para la tabla del dashboard
        $lecturas = Lectura::where('id_operador', Auth::id())
                    ->with('vivienda')
                    ->orderBy('fecha_registro', 'desc')
                    ->take(15)
                    ->get();

        return view('operador.dashboard', compact('lecturas'));
    }

    /**
     * PÁGINA 2: FORMULARIO (Esta es la función que te faltaba)
     */
    public function nuevaLectura() 
    {
        // Obtenemos las viviendas para el selector del formulario
        $viviendas = Vivienda::orderBy('nro_casa', 'asc')->get();
        
        foreach($viviendas as $v) {
            $ultima = Lectura::where('id_vivienda', $v->id_vivienda)
                             ->orderBy('id_lectura', 'desc')
                             ->first();
            $v->ultima_lectura = $ultima ? $ultima->lectura_actual : 0;
        }

        // Retornamos la vista del formulario (Asegúrate de que el nombre del archivo sea correcto)
        return view('operador.lecturas_form', compact('viviendas'));
    }

    /**
     * ACCIÓN: PROCESAR EL GUARDADO
     */
   public function guardarLectura(Request $request) 
{
    $request->validate([
        'id_vivienda'    => 'required|exists:viviendas,id_vivienda',
        'lectura_actual' => 'required|numeric|min:0',
        'mes'            => 'required|integer|between:1,12',
        'anio'           => 'required|integer'
    ]);

    DB::beginTransaction();

    try {
        // 1. VALIDACIÓN DE PROPIETARIO
        $vivienda = Vivienda::findOrFail($request->id_vivienda);
        if (!$vivienda->id_propietario) {
            throw new \Exception("La Casa #{$vivienda->nro_casa} no tiene propietario designado.");
        }

        // 2. NUEVA VALIDACIÓN: ¿YA EXISTE LECTURA PARA ESTE MES?
        $existe = Lectura::where('id_vivienda', $request->id_vivienda)
                         ->where('periodo_mes', $request->mes)
                         ->where('periodo_anio', $request->anio)
                         ->exists();
        
        if ($existe) {
            throw new \Exception("Ya se registró una lectura para la Casa #{$vivienda->nro_casa} en el periodo {$request->mes}/{$request->anio}. No puede duplicar el cobro.");
        }

        // 3. OBTENER LECTURA ANTERIOR Y CALCULAR CONSUMO
        $ultimaLectura = Lectura::where('id_vivienda', $request->id_vivienda)
                                ->orderBy('id_lectura', 'desc')->first();
        
        $lecturaAnterior = $ultimaLectura ? $ultimaLectura->lectura_actual : 0;
        $consumoM3 = $request->lectura_actual - $lecturaAnterior;

        if ($consumoM3 < 0) {
            throw new \Exception("La lectura actual ({$request->lectura_actual}) es menor a la anterior ({$lecturaAnterior}).");
        }

        // 4. OBTENER TARIFAS
        $tarifa = ConfiguracionTarifa::where('estado', true)->first();
        if (!$tarifa) {
            throw new \Exception("No hay tarifas configuradas en el sistema.");
        }

        // 5. REGISTRAR LECTURA
        $nuevaLectura = Lectura::create([
            'id_vivienda'      => $request->id_vivienda,
            'id_operador'      => Auth::id(), 
            'periodo_mes'      => $request->mes,
            'periodo_anio'     => $request->anio,
            'lectura_anterior' => $lecturaAnterior,
            'lectura_actual'   => $request->lectura_actual,
            'consumo_m3'       => $consumoM3,
        ]);

        // --- AVISO 1: AGUA ---
        $subtotalAgua = $consumoM3 * $tarifa->precio_m3_agua;
        $tieneDeudaAgua = CobroAgua::where('id_vivienda', $request->id_vivienda)
                                    ->where('estado_pago', 'Pendiente')->exists();
        $montoMora = $tieneDeudaAgua ? ($subtotalAgua * ($tarifa->porcentaje_mora / 100)) : 0;

        CobroAgua::create([
            'id_vivienda' => $request->id_vivienda,
            'id_lectura' => $nuevaLectura->id_lectura,
            'mes' => $request->mes, 'anio' => $request->anio,
            'subtotal_consumo' => $subtotalAgua,
            'monto_alcantarillado' => $tarifa->monto_alcantarillado,
            'monto_mora' => $montoMora,
            'total_pagar' => $subtotalAgua + $tarifa->monto_alcantarillado + $montoMora,
            'estado_pago' => 'Pendiente'
        ]);

        // --- AVISO 2: MANTENIMIENTO ---
        CobroMantenimiento::create([
            'id_vivienda' => $request->id_vivienda,
            'mes' => $request->mes, 'anio' => $request->anio,
            'monto_fijo' => $tarifa->monto_mantenimiento_fijo,
            'estado_pago' => 'Pendiente'
        ]);

        // --- AVISO 3: REMESAS ---
        $totalRemesa = $tarifa->monto_seguridad_base + $tarifa->monto_jardineria_base + $tarifa->monto_refacciones_base;
        CobroRemesa::create([
            'id_vivienda' => $request->id_vivienda,
            'mes' => $request->mes, 'anio' => $request->anio,
            'monto_seguridad' => $tarifa->monto_seguridad_base,
            'monto_jardineria' => $tarifa->monto_jardineria_base,
            'monto_refacciones' => $tarifa->monto_refacciones_base,
            'total_remesa' => $totalRemesa,
            'estado_pago' => 'Pendiente'
        ]);

        DB::commit(); // IMPORTANTE: Cerramos la transacción
        return redirect()->route('operador.lecturas.crear')->with('success', "¡Éxito! Lectura registrada para la Casa #{$vivienda->nro_casa}.");

    } catch (\Exception $e) {
        DB::rollBack();
        return back()->withInput()->withErrors(['error' => $e->getMessage()]);
    }
}
}