<?php

use Illuminate\Support\Facades\Route;

// 1. IMPORTACIÓN DE CONTROLADORES
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\UsuarioController;
use App\Http\Controllers\Admin\ViviendaController;
use App\Http\Controllers\Admin\AsignacionController;
use App\Http\Controllers\Admin\LecturaController;
use App\Http\Controllers\Admin\ReporteController;
use App\Http\Controllers\Admin\EgresoController;
use App\Http\Controllers\Admin\TarifaController;
use App\Http\Controllers\Operador\OperadorController;
use App\Http\Controllers\Propietario\PropietarioController;

// =========================================================
// 2. RUTAS PÚBLICAS (Accesibles sin estar logueado)
// =========================================================
Route::get('/', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.post');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');


// =========================================================
// 3. RUTAS PROTEGIDAS (Requieren inicio de sesión)
// =========================================================
Route::middleware(['auth'])->group(function () {

    // --- GRUPO ADMINISTRADOR (id_rol = 1) ---
    Route::middleware(['admin'])->prefix('admin')->group(function () {
        
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
        
        // Gestión base
        Route::resource('usuarios', UsuarioController::class)->names('admin.usuarios');
        Route::resource('viviendas', ViviendaController::class)->names('admin.viviendas');
        
        // Asignaciones y Pagos
        Route::get('/asignaciones', [AsignacionController::class, 'index'])->name('admin.asignaciones.index');
        Route::get('/asignaciones/crear', [AsignacionController::class, 'create'])->name('admin.asignaciones.create');
        Route::post('/asignaciones', [AsignacionController::class, 'store'])->name('admin.asignaciones.store');
        Route::delete('/asignaciones/{usuario}/{vivienda}', [AsignacionController::class, 'destroy'])->name('admin.asignaciones.destroy');
        Route::post('/cobros/pagar/{id}', [AdminController::class, 'registrarPago'])->name('admin.cobros.pagar');

        // Lecturas y Egresos
        Route::resource('lecturas', LecturaController::class)->names('admin.lecturas');
        Route::get('/lecturas/recibo/{id}', [LecturaController::class, 'showRecibo'])->name('admin.lecturas.recibo');
        Route::resource('egresos', EgresoController::class)->names('admin.egresos');

        // Sub-grupo Reportes
        Route::prefix('reportes')->group(function () {
            Route::get('/general', [ReporteController::class, 'index'])->name('admin.reportes.general');
            Route::get('/vivienda', [ReporteController::class, 'porVivienda'])->name('admin.reportes.vivienda');
            Route::get('/morosidad', [ReporteController::class, 'morosidad'])->name('admin.reportes.morosidad');
        });

        // Configuración
        Route::get('/configuracion/tarifas', [TarifaController::class, 'edit'])->name('admin.tarifas.edit');
        Route::post('/configuracion/tarifas', [TarifaController::class, 'update'])->name('admin.tarifas.update');
    });


    // --- GRUPO OPERADOR (id_rol = 2) ---
    Route::middleware(['operador'])->prefix('operador')->group(function () {
        Route::get('/dashboard', [OperadorController::class, 'index'])->name('operador.dashboard');
        Route::get('/lecturas/nueva', [OperadorController::class, 'nuevaLectura'])->name('operador.lecturas.crear');
        Route::post('/lecturas/guardar', [OperadorController::class, 'guardarLectura'])->name('operador.lecturas.store');
        Route::get('/averias', [OperadorController::class, 'listaAverias'])->name('operador.averias.index');
        Route::post('/averias/reportar', [OperadorController::class, 'reportarAveria'])->name('operador.averias.store');
    });


    // --- GRUPO PROPIETARIO (id_rol = 3) ---
    Route::middleware(['propietario'])->prefix('propietario')->group(function () {
        Route::get('/dashboard', [PropietarioController::class, 'index'])->name('propietario.dashboard');
        Route::get('/mis-avisos', [PropietarioController::class, 'misAvisos'])->name('propietario.avisos');
        Route::get('/reservas', [PropietarioController::class, 'misReservas'])->name('propietario.reservas.index');
        Route::post('/reservas/nueva', [PropietarioController::class, 'guardarReserva'])->name('propietario.reservas.store');
    });

});