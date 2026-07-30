@extends('layouts.admin')

@section('content')
<div class="container">
    <h2 class="mb-4">Resumen de Gestión SIDUMSS</h2>
    
    <!-- Fila de Estadísticas de Usuarios/Casas -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-dark text-white shadow">
                <div class="card-body">
                    <h6>Propietarios</h6>
                    <h3>{{ $totalUsuarios }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-secondary text-white shadow">
                <div class="card-body">
                    <h6>Viviendas</h6>
                    <h3>{{ $totalViviendas }}</h3>
                </div>
            </div>
        </div>
    </div>

    <!-- Fila de Finanzas -->
    <div class="row">
        <div class="col-md-4">
            <div class="card border-left-success shadow h-100 py-2 border-start border-success border-5">
                <div class="card-body">
                    <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Total Ingresos (Cobros)</div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800">Bs. {{ number_format($ingresos, 2) }}</div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-left-danger shadow h-100 py-2 border-start border-danger border-5">
                <div class="card-body">
                    <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Total Egresos (Gastos)</div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800">Bs. {{ number_format($egresos, 2) }}</div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-left-primary shadow h-100 py-2 border-start border-primary border-5">
                <div class="card-body">
                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Saldo en Caja</div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800">Bs. {{ number_format($saldo, 2) }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
    <!-- COLUMNA IZQUIERDA: REGISTRAR GASTO -->
    <div class="col-md-5">
        <div class="card shadow">
            <div class="card-header bg-danger text-white">
                <h5 class="mb-0">Registrar Nuevo Gasto (Egreso)</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.egresos.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Descripción / Concepto</label>
                        <input type="text" name="descripcion" class="form-control" placeholder="Ej: Sueldo Guardia Mayo" required>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Monto (Bs.)</label>
                            <input type="number" step="0.01" name="monto" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Fecha</label>
                            <input type="date" name="fecha_pago" class="form-control" value="{{ date('Y-m-d') }}" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Categoría</label>
                        <select name="categoria" class="form-select">
                            <option value="Sueldo">Sueldo</option>
                            <option value="Mantenimiento">Mantenimiento</option>
                            <option value="Servicios">Servicios (Luz/Internet)</option>
                            <option value="Otros">Otros</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-danger w-100">Guardar Gasto</button>
                </form>
            </div>
        </div>
    </div>

    <!-- COLUMNA DERECHA: ÚLTIMOS GASTOS -->
    <div class="col-md-7">
        <div class="card shadow">
            <div class="card-header bg-dark text-white">
                <h5 class="mb-0">Historial Reciente de Gastos</h5>
            </div>
            <div class="card-body">
                <table class="table table-sm">
                    <thead>
                        <tr>
                            <th>Fecha</th>
                            <th>Concepto</th>
                            <th>Categoría</th>
                            <th>Monto</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($ultimosEgresos as $eg)
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($eg->fecha_pago)->format('d/m/y') }}</td>
                            <td>{{ $eg->descripcion }}</td>
                            <td><span class="badge bg-secondary">{{ $eg->categoria }}</span></td>
                            <td class="text-danger">Bs. {{ number_format($eg->monto, 2) }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center">No hay gastos registrados.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

    <!-- Debajo de la fila de finanzas -->
<div class="row mt-5">
    <div class="col-md-12">
        <div class="card shadow">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Cobros Pendientes Recientes</h5>
            </div>
            <div class="card-body">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Casa</th>
                            <th>Periodo</th>
                            <th>Monto Total</th>
                            <th>Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($deudasPendientes as $deuda)
                        <tr>
                            <td>{{ $deuda->vivienda->nro_casa }}</td>
                            <td>{{ $deuda->periodo_mes }}/{{ $deuda->periodo_anio }}</td>
                            <td class="fw-bold text-danger">Bs. {{ number_format($deuda->total_pagar, 2) }}</td>
                            <td>
                                <form action="{{ route('admin.cobros.pagar', $deuda->id_cobro) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-success">
                                        <i class="fas fa-check"></i> Marcar como Pagado
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center">No hay deudas pendientes actualmente.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
</div>
@endsection