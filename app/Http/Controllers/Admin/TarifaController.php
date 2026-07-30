<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Tarifa;
use Illuminate\Support\Facades\DB;

class TarifaController extends Controller
{
    public function edit()
    {
        // Buscamos la última tarifa registrada
        $tarifa = DB::table('tarifas')->latest('id_tarifa')->first();
        return view('admin.tarifas.edit', compact('tarifa'));
    }

    public function update(Request $request)
    {
        // Lógica para actualizar (la haremos luego)
        return redirect()->back()->with('success', 'Tarifas actualizadas.');
    }
}