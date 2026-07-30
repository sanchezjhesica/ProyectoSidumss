<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UsuarioController extends Controller
{
    public function index()
{
    // Solo traemos a los usuarios que tengan el estado_logico en true (1)
    $usuarios = User::where('estado_logico', true)->get();
    return view('admin.usuarios.index', compact('usuarios'));
}

    public function create() {
        return view('admin.usuarios.create');
    }

        // Guarda los datos en la base de datos
    public function store(Request $request)
    {
        // 1. Validar los datos (según tu SQL)
        $request->validate([
            'nombre' => 'required|string|max:100',
            'ci' => 'required|string|max:20|unique:usuarios,ci',
            'email' => 'required|email|max:100|unique:usuarios,email',
            'password' => 'required|min:6',
        ]);

        // 2. Crear el usuario usando Eloquent
        User::create([
            'nombre' => $request->nombre,
            'apellido_paterno' => $request->apellido_paterno,
            'apellido_materno' => $request->apellido_materno,
            'ci' => $request->ci,
            'telefono' => $request->telefono,
            'email' => $request->email,
            'password' => Hash::make($request->password), // Encriptar siempre
            'id_rol' => 3, // Asignamos 3 (Propietario) por defecto
            'estado_logico' => true
        ]);

        // 3. Redireccionar con mensaje de éxito
        return redirect()->route('admin.usuarios.index')->with('success', '¡Propietario registrado con éxito!');
    }

    public function edit($id) {
        $usuario = User::findOrFail($id);
        return view('admin.usuarios.edit', compact('usuario'));
    }

    public function update(Request $request, $id) {
        $usuario = User::findOrFail($id);
        $usuario->update($request->all());
        return redirect()->route('admin.usuarios.index')->with('success', 'Usuario actualizado.');
    }

    public function destroy($id)
{
    $usuario = User::findOrFail($id);
    
    // En lugar de borrarlo de la base de datos, lo desactivamos
    $usuario->estado_logico = false; 
    $usuario->save();

    return redirect()->route('admin.usuarios.index')->with('success', 'Usuario desactivado correctamente.');
}
}