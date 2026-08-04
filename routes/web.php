<?php

use Illuminate\Support\Facades\Route;
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

Route::get('/', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.post');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
Route::middleware(['auth'])->group(function () {

Route::get('/recibo/detalle/{id_cobro}', [LecturaController::class, 'showRecibo'])->name('compartido.recibo');

    // --- GRUPO ADMINISTRADOR (id_rol = 1) ---
    Route::middleware(['admin'])->prefix('admin')->group(function () {
        
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
        Route::resource('usuarios', UsuarioController::class)->names('admin.usuarios');
        Route::resource('viviendas', ViviendaController::class)->names('admin.viviendas');
        
        Route::get('/asignaciones', [AsignacionController::class, 'index'])->name('admin.asignaciones.index');
        Route::get('/asignaciones/crear', [AsignacionController::class, 'create'])->name('admin.asignaciones.create');
        Route::post('/asignaciones', [AsignacionController::class, 'store'])->name('admin.asignaciones.store');
        Route::delete('/asignaciones/{usuario}/{vivienda}', [AsignacionController::class, 'destroy'])->name('admin.asignaciones.destroy');
        Route::post('/cobros/pagar/{id}', [AdminController::class, 'registrarPago'])->name('admin.cobros.pagar');

        Route::resource('lecturas', LecturaController::class)->names('admin.lecturas');
        
        Route::resource('egresos', EgresoController::class)->names('admin.egresos');

        Route::prefix('reportes')->group(function () {
            Route::get('/general', [ReporteController::class, 'index'])->name('admin.reportes.general');
            Route::get('/vivienda', [ReporteController::class, 'porVivienda'])->name('admin.reportes.vivienda');
            Route::get('/morosidad', [ReporteController::class, 'morosidad'])->name('admin.reportes.morosidad');
        });

        Route::get('/configuracion/tarifas', [TarifaController::class, 'edit'])->name('admin.tarifas.edit');
        Route::post('/configuracion/tarifas', [TarifaController::class, 'update'])->name('admin.tarifas.update');
    });


    // --- GRUPO OPERADOR (id_rol = 2) ---
    Route::middleware(['operador'])->prefix('operador')->group(function () {
        Route::get('/dashboard', [OperadorController::class, 'index'])->name('operador.dashboard');
        Route::get('/lecturas/nueva', [OperadorController::class, 'nuevaLectura'])->name('operador.lecturas.crear');
        Route::post('/lecturas/guardar', [OperadorController::class, 'guardarLectura'])->name('operador.lecturas.store');
        Route::get('/averias', [OperadorController::class, 'listaAverias'])->name('operador.averias.index');
    });


    // --- GRUPO PROPIETARIO (id_rol = 3) ---
    Route::middleware(['propietario'])->prefix('propietario')->group(function () {
        Route::get('/dashboard', [PropietarioController::class, 'index'])->name('propietario.dashboard');
        Route::get('/mis-avisos', [PropietarioController::class, 'misAvisos'])->name('propietario.avisos');
        Route::get('/reservas', [PropietarioController::class, 'misReservas'])->name('propietario.reservas.index');
        Route::post('/reservas/nueva', [PropietarioController::class, 'guardarReserva'])->name('propietario.reservas.store');
    });

});