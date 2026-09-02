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
    $usuarios = User::where('estado_logico', true)->get();
    return view('admin.usuarios.index', compact('usuarios'));
}

    public function create() {
        return view('admin.usuarios.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => ['required', 'string', 'max:100', 'regex:/^[\pL\s\-]+$/u'],
            'apellido_paterno' => ['nullable', 'string', 'max:100', 'regex:/^[\pL\s\-]+$/u'],
            'apellido_materno' => ['nullable', 'string', 'max:100', 'regex:/^[\pL\s\-]+$/u'],
            'ci' => 'required|numeric|digits_between:5,15|unique:usuarios,ci',
            'telefono' => 'nullable|numeric|digits_between:7,12',
            'email' => 'required|email:rfc,dns|max:100|unique:usuarios,email',
            'password' => 'required|string|min:8|confirmed',
        ], [
            // MENSAJES PARA NOMBRE Y APELLIDOS
            'nombre.required' => 'El nombre es obligatorio.',
            'nombre.regex' => 'El nombre solo debe contener letras.',
            'apellido_paterno.regex' => 'El apellido paterno solo debe contener letras.',
            'apellido_materno.regex' => 'El apellido materno solo debe contener letras.',

            // MENSAJES PARA CI
            'ci.required' => 'La cédula de identidad es obligatoria.',
            'ci.numeric' => 'La cédula de identidad debe contener solo números.',
            'ci.digits_between' => 'La cédula de identidad debe tener entre 5 y 15 dígitos.',
            'ci.unique' => 'Este número de CI ya está registrado en el sistema.',

            // MENSAJES PARA TELÉFONO
            'telefono.numeric' => 'El teléfono debe contener solo números.',
            'telefono.digits_between' => 'El teléfono debe tener entre 7 y 12 dígitos.',

            // MENSAJES PARA EMAIL
            'email.required' => 'El correo electrónico es obligatorio.',
            'email.email' => 'El formato del correo electrónico no es válido.',
            'email.dns' => 'El dominio del correo (ej. @gmail.com) no existe o no es real.',
            'email.unique' => 'Este correo electrónico ya está registrado por otro usuario.',

            // MENSAJES PARA CONTRASEÑA
            'password.required' => 'La contraseña es obligatoria.',
            'password.min' => 'La contraseña debe tener al menos 8 caracteres.',
            'password.confirmed' => 'La confirmación de la contraseña no coincide.',
        ]);

        User::create([
            'nombre' => $request->nombre,
            'apellido_paterno' => $request->apellido_paterno,
            'apellido_materno' => $request->apellido_materno,
            'ci' => $request->ci,
            'telefono' => $request->telefono,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'id_rol' => 3,
            'estado_logico' => true
        ]);

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
    $usuario->estado_logico = false; 
    $usuario->save();

    return redirect()->route('admin.usuarios.index')->with('success', 'Usuario desactivado correctamente.');
}
public function resetPassword($id)
{
    $usuario = User::findOrFail($id);
    
    // Establecemos la contraseña predeterminada
    $usuario->password = Hash::make('sidumss123');
    $usuario->save();

    return redirect()->route('admin.usuarios.index')
        ->with('success', "La contraseña de {$usuario->nombre} ha sido restablecida a: sidumss123");
}
}