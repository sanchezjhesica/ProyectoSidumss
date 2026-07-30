<?php

use Illuminate\Support\Facades\Route;

// Importación de controladores
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

// 1. RUTA RAÍZ
Route::get('/', function () {
    return view('welcome');
});

// 2. GRUPO ADMINISTRADOR (Todo empieza con /admin/...)
Route::prefix('admin')->group(function () {
    
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::resource('usuarios', UsuarioController::class)->names('admin.usuarios');
    Route::resource('viviendas', ViviendaController::class)->names('admin.viviendas');
    
    // Asignaciones
    Route::get('/asignaciones', [AsignacionController::class, 'index'])->name('admin.asignaciones.index');
    Route::get('/asignaciones/crear', [AsignacionController::class, 'create'])->name('admin.asignaciones.create');
    Route::post('/asignaciones', [AsignacionController::class, 'store'])->name('admin.asignaciones.store');
    Route::delete('/asignaciones/{usuario}/{vivienda}', [AsignacionController::class, 'destroy'])->name('admin.asignaciones.destroy');

    // Lecturas y Recibos
    Route::resource('lecturas', LecturaController::class)->names('admin.lecturas');
    Route::get('/lecturas/recibo/{id}', [LecturaController::class, 'showRecibo'])->name('admin.lecturas.recibo');

    // Finanzas
    Route::resource('egresos', EgresoController::class)->names('admin.egresos');
    Route::post('/cobros/pagar/{id}', [AdminController::class, 'registrarPago'])->name('admin.cobros.pagar');

    // Reportes (Sub-grupo)
    Route::prefix('reportes')->group(function () {
        Route::get('/general', [ReporteController::class, 'index'])->name('admin.reportes.general');
        Route::get('/vivienda', [ReporteController::class, 'porVivienda'])->name('admin.reportes.vivienda');
        Route::get('/morosidad', [ReporteController::class, 'morosidad'])->name('admin.reportes.morosidad');
    });

    // Tarifas
    Route::get('/configuracion/tarifas', [TarifaController::class, 'edit'])->name('admin.tarifas.edit');
    Route::post('/configuracion/tarifas', [TarifaController::class, 'update'])->name('admin.tarifas.update');

}); // Aquí termina el grupo Admin

// 3. GRUPO OPERADOR (Limpio y sin duplicados)
Route::prefix('operador')->group(function () {
    Route::get('/dashboard', [OperadorController::class, 'index'])->name('operador.dashboard');
    
    // Registro de Lecturas
    Route::get('/lecturas/nueva', [OperadorController::class, 'nuevaLectura'])->name('operador.lecturas.crear');
    Route::post('/lecturas/guardar', [OperadorController::class, 'guardarLectura'])->name('operador.lecturas.store');

    // Reporte de Averías
    Route::get('/averias', [OperadorController::class, 'listaAverias'])->name('operador.averias.index');
    Route::post('/averias/reportar', [OperadorController::class, 'reportarAveria'])->name('operador.averias.store');
});
//rutas del propietario
Route::prefix('propietario')->group(function () {
    Route::get('/dashboard', [PropietarioController::class, 'index'])->name('propietario.dashboard');
    
    // Ver facturas y avisos
    Route::get('/mis-avisos', [PropietarioController::class, 'misAvisos'])->name('propietario.avisos');
    
    // Reservas de áreas comunes
    Route::get('/reservas', [PropietarioController::class, 'misReservas'])->name('propietario.reservas.index');
    Route::post('/reservas/nueva', [PropietarioController::class, 'guardarReserva'])->name('propietario.reservas.store');
});