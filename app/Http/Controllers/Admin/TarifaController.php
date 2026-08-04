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
    
        $tarifa = DB::table('tarifas')->latest('id_tarifa')->first();
        return view('admin.tarifas.edit', compact('tarifa'));
    }

    public function update(Request $request)
    {
        return redirect()->back()->with('success', 'Tarifas actualizadas.');
    }
}