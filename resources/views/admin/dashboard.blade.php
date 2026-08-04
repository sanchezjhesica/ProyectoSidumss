@extends('layouts.admin')

@section('content')
<div class="dashboard-stellar">
    <h2 class="mb-5"><i class="fas fa-tachometer-alt"></i> Resumen de Gestión SIDUMSS</h2>
    
    <!-- Fila de Estadísticas Rápidas -->
    <div class="row mb-5 text-center">
        <div class="col-md-3">
            <div class="stat-box shadow-sm">
                <div class="icon-circle bg-purple-light"><i class="fas fa-users"></i></div>
                <h6 class="text-muted mt-3">Propietarios</h6>
                <h3 class="fw-bold">{{ $totalUsuarios }}</h3>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-box shadow-sm">
                <div class="icon-circle bg-purple-light"><i class="fas fa-home"></i></div>
                <h6 class="text-muted mt-3">Viviendas</h6>
                <h3 class="fw-bold">{{ $totalViviendas }}</h3>
            </div>
        </div>
    </div>

    <!-- Fila de Finanzas (Cards Limpias) -->
    <div class="row mb-5">
        <div class="col-md-4">
            <div class="card shadow-sm border-0 border-bottom border-success border-5 h-100">
                <div class="card-body p-4 text-center">
                    <div class="text-uppercase small fw-bold text-success mb-2">Total Ingresos</div>
                    <div class="h3 mb-0 fw-bold text-dark">Bs. {{ number_format($ingresos, 2) }}</div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm border-0 border-bottom border-danger border-5 h-100">
                <div class="card-body p-4 text-center">
                    <div class="text-uppercase small fw-bold text-danger mb-2">Total Egresos</div>
                    <div class="h3 mb-0 fw-bold text-dark">Bs. {{ number_format($egresos, 2) }}</div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm border-0 border-bottom border-primary border-5 h-100">
                <div class="card-body p-4 text-center">
                    <div class="text-uppercase small fw-bold text-primary mb-2">Saldo en Caja</div>
                    <div class="h3 mb-0 fw-bold text-dark">Bs. {{ number_format($saldo, 2) }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <!-- REGISTRAR GASTO -->
        <div class="col-md-5">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white py-3 border-bottom">
                    <h5 class="mb-0 text-purple fw-bold"><i class="fas fa-plus-circle"></i> Nuevo Gasto</h5>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('admin.egresos.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label small fw-bold">Descripción / Concepto</label>
                            <input type="text" name="descripcion" class="form-control form-stellar" placeholder="Ej: Sueldo Guardia Mayo" required>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label small fw-bold">Monto (Bs.)</label>
                                <input type="number" step="0.01" name="monto" class="form-control form-stellar" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label small fw-bold">Fecha</label>
                                <input type="date" name="fecha_pago" class="form-control form-stellar" value="{{ date('Y-m-d') }}" required>
                            </div>
                        </div>
                        <div class="mb-4">
                            <label class="form-label small fw-bold">Categoría</label>
                            <select name="categoria" class="form-select form-stellar">
                                <option value="Sueldo">Sueldo</option>
                                <option value="Mantenimiento">Mantenimiento</option>
                                <option value="Servicios">Servicios (Luz/Internet)</option>
                                <option value="Otros">Otros</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-purple-stellar w-100">Guardar Gasto</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- HISTORIAL DE GASTOS -->
        <div class="col-md-7">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white py-3 border-bottom">
                    <h5 class="mb-0 text-purple fw-bold"><i class="fas fa-history"></i> Historial Reciente</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0 align-middle">
                            <thead class="bg-light">
                                <tr>
                                    <th class="ps-4">Fecha</th>
                                    <th>Concepto</th>
                                    <th>Monto</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($ultimosEgresos as $eg)
                                <tr>
                                    <td class="ps-4">{{ \Carbon\Carbon::parse($eg->fecha_pago)->format('d/m/y') }}</td>
                                    <td>
                                        <div class="fw-bold text-dark">{{ $eg->descripcion }}</div>
                                        <small class="text-muted">{{ $eg->categoria }}</small>
                                    </td>
                                    <td class="text-danger fw-bold">Bs. {{ number_format($eg->monto, 2) }}</td>
                                </tr>
                                @empty
                                <tr><td colspan="3" class="p-4 text-center">No hay gastos.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- COBROS PENDIENTES -->
    <div class="row mt-5">
        <div class="col-md-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 text-purple fw-bold"><i class="fas fa-clock"></i> Cobros Pendientes</h5>
                    <span class="badge bg-purple-light text-purple">Pendientes</span>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0 align-middle">
                            <thead class="bg-light">
                                <tr>
                                    <th class="ps-4">Nro Casa</th>
                                    <th>Periodo</th>
                                    <th>Monto a Pagar</th>
                                    <th class="text-center">Acción</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($deudasPendientes as $deuda)
                                <tr>
                                    <td class="ps-4 fw-bold">Casa #{{ $deuda->vivienda->nro_casa }}</td>
                                    <td>{{ $deuda->periodo_mes }}/{{ $deuda->periodo_anio }}</td>
                                    <td class="fw-bold text-danger">Bs. {{ number_format($deuda->total_pagar, 2) }}</td>
                                    <td class="text-center">
                                        <form action="{{ route('admin.cobros.pagar', $deuda->id_cobro) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-outline-success rounded-pill px-3">
                                                <i class="fas fa-check"></i> Marcar Pagado
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr><td colspan="4" class="p-5 text-center">Sin deudas pendientes.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* Estilos internos para el Dashboard Estilo Stellar */
    .text-purple { color: #5f4d93; }
    .bg-purple-light { background: rgba(95, 77, 147, 0.1); color: #5f4d93; }
    
    .stat-box {
        background: #fdfdfd;
        padding: 20px;
        border-radius: 15px;
        border: 1px solid #eee;
        transition: transform 0.3s;
    }
    .stat-box:hover { transform: translateY(-5px); }
    
    .icon-circle {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto;
        font-size: 1.5rem;
    }

    .form-stellar {
        border: 1px solid #ddd;
        border-radius: 8px;
        padding: 10px;
    }
    
    .btn-purple-stellar {
        background: #5f4d93;
        color: white;
        border-radius: 50px;
        font-weight: bold;
        padding: 12px;
        border: none;
        transition: 0.3s;
    }
    .btn-purple-stellar:hover {
        background: #4a3b75;
        box-shadow: 0 5px 15px rgba(95, 77, 147, 0.3);
    }
</style>
@endsection