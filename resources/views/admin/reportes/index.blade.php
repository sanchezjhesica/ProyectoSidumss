@extends('layouts.admin')

@section('content')
<div class="reporte-stellar">
    <!-- Cabecera del Reporte -->
    <div class="d-flex justify-content-between align-items-center mb-5 border-bottom pb-4">
        <div>
            <h2 class="text-purple mb-0"><i class="fas fa-file-contract me-2"></i> Reporte General del Sistema</h2>
            <p class="text-muted mb-0">Resumen consolidado de consumos, ingresos y estados de cuenta.</p>
        </div>
        <div class="no-print">
            <button onclick="window.print()" class="btn btn-purple-stellar">
                <i class="fas fa-print me-2"></i> Imprimir Reporte
            </button>
        </div>
    </div>

    <!-- Bloque de Estadísticas (Cards Estilo Stellar) -->
    <div class="row mb-5">
        <div class="col-md-4">
            <div class="stat-box shadow-sm text-center">
                <div class="icon-circle bg-purple-light"><i class="fas fa-hand-holding-usd"></i></div>
                <h6 class="text-muted mt-3">Total Ingresos</h6>
                <h3 class="fw-bold text-dark">Bs. {{ number_format($totalIngresos ?? 0, 2) }}</h3>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-box shadow-sm text-center">
                <div class="icon-circle bg-red-light"><i class="fas fa-file-invoice-dollar"></i></div>
                <h6 class="text-muted mt-3">Cobros Pendientes</h6>
                <h3 class="fw-bold text-danger">Bs. {{ number_format($totalPendiente ?? 0, 2) }}</h3>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-box shadow-sm text-center">
                <div class="icon-circle bg-blue-light"><i class="fas fa-users"></i></div>
                <h6 class="text-muted mt-3">Usuarios Activos</h6>
                <h3 class="fw-bold text-dark">{{ $totalUsuarios ?? 0 }}</h3>
            </div>
        </div>
    </div>

    <!-- Tabla de Detalles -->
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="card-header bg-white py-3">
            <h5 class="mb-0 text-purple fw-bold">Detalle Reciente de Movimientos</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4">Periodo</th>
                            <th>Nro Casa</th>
                            <th>Monto</th>
                            <th class="text-center">Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        {{-- Aquí recorremos la lista que mande tu controlador, por ejemplo $lecturas o $reporte --}}
                        @isset($datosReporte)
                            @foreach($datosReporte as $item)
                            <tr>
                                <td class="ps-4">{{ $item->periodo }}</td>
                                <td>Casa #{{ $item->nro_casa }}</td>
                                <td class="fw-bold">Bs. {{ number_format($item->monto, 2) }}</td>
                                <td class="text-center">
                                    <span class="badge rounded-pill {{ $item->estado == 'Pagado' ? 'bg-success' : 'bg-danger' }} px-3">
                                        {{ $item->estado }}
                                    </span>
                                </td>
                            </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="4" class="text-center py-5 text-muted">
                                    No hay datos disponibles para mostrar en este reporte.
                                </td>
                            </tr>
                        @endisset
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<style>
    .text-purple { color: #5f4d93; }
    .bg-purple-light { background: rgba(95, 77, 147, 0.1); color: #5f4d93; }
    .bg-red-light { background: rgba(220, 53, 69, 0.1); color: #dc3545; }
    .bg-blue-light { background: rgba(13, 110, 253, 0.1); color: #0d6efd; }

    .stat-box {
        background: #fff;
        padding: 25px;
        border-radius: 15px;
        border: 1px solid #eee;
    }

    .icon-circle {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto;
        font-size: 1.2rem;
    }

    .btn-purple-stellar {
        background: #5f4d93;
        color: white;
        border-radius: 50px;
        padding: 10px 25px;
        font-weight: bold;
        border: none;
    }

    .rounded-4 { border-radius: 1rem !important; }

    @media print {
        .no-print, nav, #header-stellar { display: none !important; }
        body { background: white !important; }
        .main-wrapper { max-width: 100% !important; margin: 0 !important; padding: 0 !important; }
        .main-card { box-shadow: none !important; border: none !important; }
        .stat-box { border: 1px solid #000 !important; }
    }
</style>
@endsection