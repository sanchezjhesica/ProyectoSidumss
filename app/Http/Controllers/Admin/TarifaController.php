<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ConfiguracionTarifa;
use App\Models\RemesaConfig;
use App\Models\AreaRecreativa; // Asegúrate de tener este modelo
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class TarifaController extends Controller
{
    public function edit()
    {
        // 1. Datos de Agua y Mantenimiento
        $tarifa = ConfiguracionTarifa::orderBy('id_config', 'desc')->first();
        
        // 2. Lista de Remesas (Seguridad, Jardín, etc.)
        $remesas = RemesaConfig::all();
        
        // 3. Lista de Áreas (Salón, Wally)
        $areas = AreaRecreativa::all();

        return view('admin.tarifas.edit', compact('tarifa', 'remesas', 'areas'));
    }

    // Actualizar Agua y Mantenimiento
    public function updateGlobal(Request $request) {
        ConfiguracionTarifa::create([
            'precio_m3_agua' => $request->precio_m3_agua,
            'monto_alcantarillado' => $request->monto_alcantarillado,
            'monto_mantenimiento_fijo' => $request->monto_mantenimiento_fijo,
            'porcentaje_mora' => $request->porcentaje_mora,
            'fecha_aplicacion' => now(),
            'estado' => 1
        ]);
        return back()->with('success', 'Tarifas globales actualizadas.');
    }

    // Actualizar Precios de Remesas
    public function updateRemesa(Request $request, $id) {
        $remesa = RemesaConfig::findOrFail($id);
        $remesa->update(['monto_estandar' => $request->monto_estandar]);
        return back()->with('success', 'Precio de ' . $remesa->nombre_remesa . ' actualizado.');
    }

    // Actualizar Precios de Áreas
    public function updateArea(Request $request, $id) {
        $area = AreaRecreativa::findOrFail($id);
        $area->update(['costo_reserva' => $request->costo_reserva]);
        return back()->with('success', 'Precio de ' . $area->nombre_area . ' actualizado.');
    }
public function updateQR(Request $request)
{
    // 1. Validar imagen
    $request->validate([
        'qr_pago' => 'required|image|mimes:jpg,png,jpeg|max:2048'
    ]);

    // 2. Buscar la configuración activa
    // Usamos DB::table porque estás usando Query Builder según tus capturas
    $config = DB::table('configuracion_tarifas')->where('estado', true)->first();

    // Validar si existe el registro para evitar error "on null"
    if (!$config) {
        return back()->with('error', 'No hay una configuración activa. Primero guarde las tarifas globales.');
    }

    if ($request->hasFile('qr_pago')) {
        
        // USAMOS isset() PARA COMPROBAR SI LA PROPIEDAD EXISTE EN EL OBJETO
        if (isset($config->qr_pago) && $config->qr_pago) {
            Storage::disk('public')->delete($config->qr_pago);
        }

        // Guardar nueva imagen
        $ruta = $request->file('qr_pago')->store('qrs', 'public');

        // 3. Actualizar la base de datos
        DB::table('configuracion_tarifas')
            ->where('id_config', $config->id_config)
            ->update(['qr_pago' => $ruta]);

        return back()->with('success', '¡Código QR actualizado con éxito!');
    }

    return back()->with('error', 'Error al procesar la imagen.');
}
}