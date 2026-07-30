<?php

namespace App\Http\Controllers\Propietario;

use App\Http\Controllers\Controller;
use App\Models\Vivienda;
use App\Models\Cobro;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PropietarioController extends Controller
{
    public function index() {
        // ID de prueba (En el futuro será Auth::id())
        $id_usuario = 2; 
        $viviendas = Vivienda::whereHas('propietarios', function($q) use($id_usuario) {
            $q->where('propietario_vivienda.id_usuario', $id_usuario);
        })->get();

        return view('propietario.dashboard', compact('viviendas'));
    }

    public function misAvisos() {
        $id_usuario = 2;
        // Obtenemos los cobros de las viviendas que pertenecen a este usuario
        $avisos = Cobro::whereIn('id_vivienda', function($query) use($id_usuario) {
            $query->select('id_vivienda')->from('propietario_vivienda')->where('id_usuario', $id_usuario);
        })->orderBy('periodo_anio', 'desc')->get();

        return view('propietario.avisos', compact('avisos'));
    }

    public function misReservas() {
        $id_usuario = 2;
        $reservas = DB::table('reservas')->where('id_usuario', $id_usuario)->get();
        $areas = DB::table('areas_recreativas')->get();
        return view('propietario.reservas', compact('reservas', 'areas'));
    }

    public function guardarReserva(Request $request) {
        DB::table('reservas')->insert([
            'id_usuario' => 2,
            'id_area' => $request->id_area,
            'fecha_reserva' => $request->fecha,
            'costo_pactado' => DB::table('areas_recreativas')->where('id_area', $request->id_area)->value('costo_estandar'),
            'estado_pago' => 'Pendiente'
        ]);
        return redirect()->back()->with('success', 'Reserva realizada. Se incluirá en su próximo aviso de cobro.');
    }
}
