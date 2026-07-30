<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showLoginForm() {
        return view('auth.login');
    }

    public function login(Request $request) {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // Intentar iniciar sesión
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            $user = Auth::user();

            // REDIRECCIÓN SEGÚN ROL (id_rol de tu tabla usuarios)
            if ($user->id_rol == 1) {
                return redirect()->intended('admin/dashboard');
            } elseif ($user->id_rol == 2) {
                return redirect()->intended('operador/dashboard');
            } elseif ($user->id_rol == 3) {
                return redirect()->intended('propietario/dashboard');
            }
        }

        // Si falla el login
        return back()->withErrors([
            'email' => 'Las credenciales no coinciden con nuestros registros.',
        ]);
    }

    public function logout(Request $request) {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}