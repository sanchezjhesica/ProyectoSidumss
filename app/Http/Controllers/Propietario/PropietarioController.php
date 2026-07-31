<?php

namespace App\Http\Controllers\Propietario;

use App\Http\Controllers\Controller;
use App\Models\Vivienda;
use App\Models\Cobro;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth; // Asegura que esta línea esté aquí

class PropietarioController extends Controller
{
    /**
     * Dashboard del Propietario: Muestra sus casas asignadas.
     */
    public function index() {
        // Obtenemos el ID del usuario logueado actualmente
        $id_usuario = Auth::id(); 

        $viviendas = Vivienda::whereHas('propietarios', function($q) use($id_usuario) {
            $q->where('propietario_vivienda.id_usuario', $id_usuario);
        })->get();

        return view('propietario.dashboard', compact('viviendas'));
    }

    /**
     * Muestra solo los avisos de cobro de las casas del propietario logueado.
     */
    public function misAvisos() {
        $id_usuario = Auth::id();

        // Buscamos los cobros filtrando por las viviendas del usuario
        $avisos = Cobro::whereIn('id_vivienda', function($query) use ($id_usuario) {
            $query->select('id_vivienda')
                  ->from('propietario_vivienda')
                  ->where('id_usuario', $id_usuario);
        })->orderBy('periodo_anio', 'desc')
          ->orderBy('periodo_mes', 'desc')
          ->get();

        return view('propietario.avisos', compact('avisos'));
    }

    /**
     * Muestra el historial de reservas del propietario logueado.
     */
    public function misReservas() {
        $id_usuario = Auth::id();

        // Obtenemos sus reservas y la lista de áreas disponibles
        $reservas = DB::table('reservas')
            ->join('areas_recreativas', 'reservas.id_area', '=', 'areas_recreativas.id_area')
            ->where('reservas.id_usuario', $id_usuario)
            ->select('reservas.*', 'areas_recreativas.nombre_area')
            ->orderBy('fecha_reserva', 'desc')
            ->get();

        $areas = DB::table('areas_recreativas')->get();

        return view('propietario.reservas', compact('reservas', 'areas'));
    }

    /**
     * Guarda una nueva reserva para el propietario logueado.
     */
    public function guardarReserva(Request $request) {
        $request->validate([
            'id_area' => 'required',
            'fecha' => 'required|date|after_or_equal:today',
        ]);

        $id_usuario = Auth::id();

        // Obtenemos el precio actual del área seleccionada
        $costo = DB::table('areas_recreativas')
                   ->where('id_area', $request->id_area)
                   ->value('costo_estandar');

        DB::table('reservas')->insert([
            'id_usuario' => $id_usuario, // ID dinámico
            'id_area' => $request->id_area,
            'fecha_reserva' => $request->fecha,
            'costo_pactado' => $costo,
            'estado_pago' => 'Pendiente',
            'created_at' => now()
        ]);

        return redirect()->back()->with('success', 'Reserva realizada con éxito. El monto se cargará en su próximo aviso de cobro.');
    }
}