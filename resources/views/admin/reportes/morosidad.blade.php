@extends('layouts.admin')

@section('content')
<div class="morosidad-stellar">
    <!-- Cabecera del Reporte -->
    <div class="d-flex justify-content-between align-items-center mb-5 border-bottom pb-4">
        <div>
            <h2 class="text-danger-stellar mb-0">
                <i class="fas fa-exclamation-triangle me-2"></i> Reporte de Morosidad
            </h2>
            <p class="text-muted mb-0">Lista de propietarios con pagos pendientes y deudas acumuladas.</p>
        </div>
        <div class="no-print">
            <button onclick="window.print()" class="btn btn-danger-stellar px-4 shadow-sm">
                <i class="fas fa-print me-2"></i> Imprimir Lista
            </button>
        </div>
    </div>

    <!-- Resumen de Deuda (Stats) -->
    <div class="row mb-5">
        <div class="col-md-6">
            <div class="stat-box-danger shadow-sm d-flex align-items-center p-4">
                <div class="icon-circle-danger me-4">
                    <i class="fas fa-money-bill-wave"></i>
                </div>
                <div>
                    <h6 class="text-muted mb-1 text-uppercase small fw-bold">Total Deuda Global</h6>
                    <!-- Total Deuda Global -->
                    <h2 class="fw-bold mb-0 text-danger-stellar">
                        Bs. {{ number_format($morosos->sum('total_deuda'), 2) }}
                    </h2>

                    <!-- Casas con Deuda -->
                    <h2 class="fw-bold mb-0 text-purple">{{ $morosos->count() }}</h2>
                                    </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="stat-box-stellar shadow-sm d-flex align-items-center p-4">
                <div class="icon-circle-stellar me-4">
                    <i class="fas fa-user-clock"></i>
                </div>
                <div>
                    <h6 class="text-muted mb-1 text-uppercase small fw-bold">Casas con Deuda</h6>
                    <h2 class="fw-bold mb-0 text-purple">{{ $morosos->count() }}</h2>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabla de Deudores -->
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="card-header bg-white py-3 border-bottom">
            <h5 class="mb-0 fw-bold text-dark"><i class="fas fa-list me-2"></i> Detalle de Cobros Pendientes</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4 py-3 uppercase-tracking">Casa</th>
                            <th class="py-3 uppercase-tracking">Propietario</th>
                            <th class="py-3 uppercase-tracking">Periodo</th>
                            <th class="py-3 uppercase-tracking">Monto</th>
                            <th class="py-3 text-center uppercase-tracking">Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($morosos as $m)
                        <tr>
                            <td class="ps-4 fw-bold">
                                <span class="badge bg-purple-soft text-purple px-3 py-2">Casa #{{ $m->nro_casa }}</span>
                            </td>
                            <td>
                                {{-- RELACIÓN CORREGIDA: de propietario (singular) --}}
                                <div class="fw-bold text-dark">{{ $m->propietario->nombre ?? 'Sin Propietario' }} {{ $m->propietario->apellido_paterno ?? '' }}</div>
                                <small class="text-muted">CI: {{ $m->propietario->ci ?? '---' }}</small>
                            </td>
                            <td>
                                <span class="text-dark">{{ $m->cantidad_avisos }} avisos pendientes</span>
                            </td>
                            <td class="text-danger-stellar fw-bold fs-6">
                                {{-- VARIABLE CORREGIDA: total_deuda --}}
                                Bs. {{ number_format($m->total_deuda, 2) }}
                            </td>
                            <td class="text-center">
                                <span class="badge rounded-pill bg-danger-soft text-danger-stellar px-3 py-2">
                                    <i class="fas fa-clock me-1"></i> PENDIENTE
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-5">
                                <div class="text-success fs-3 mb-2"><i class="fas fa-check-circle"></i></div>
                                <p class="text-muted mb-0">No se encontraron cobros pendientes. ¡Todos están al día!</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<style>
    /* Estilos Específicos para Morosidad Stellar */
    .text-danger-stellar { color: #e74c3c; }
    .text-purple { color: #5f4d93; }
    .bg-danger-soft { background: rgba(231, 76, 60, 0.08); }
    .bg-purple-soft { background: rgba(95, 77, 147, 0.08); }

    .uppercase-tracking {
        font-size: 0.7rem;
        text-transform: uppercase;
        letter-spacing: 1px;
        font-weight: 700;
        color: #888;
    }

    .stat-box-danger { background: #fff; border-radius: 15px; border-left: 5px solid #e74c3c; }
    .stat-box-stellar { background: #fff; border-radius: 15px; border-left: 5px solid #5f4d93; }

    .icon-circle-danger {
        width: 55px; height: 55px;
        background: rgba(231, 76, 60, 0.1);
        color: #e74c3c;
        border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.4rem;
    }
    .icon-circle-stellar {
        width: 55px; height: 55px;
        background: rgba(95, 77, 147, 0.1);
        color: #5f4d93;
        border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.4rem;
    }

    .btn-danger-stellar {
        background: #e74c3c;
        color: white;
        border-radius: 50px;
        font-weight: bold;
        border: none;
        transition: 0.3s;
    }
    .btn-danger-stellar:hover { background: #c0392b; color: white; transform: translateY(-2px); }

    .rounded-4 { border-radius: 1rem !important; }

    @media print {
        .no-print, nav, #header-stellar { display: none !important; }
        body { background: white !important; }
        .main-wrapper { max-width: 100% !important; margin: 0 !important; padding: 0 !important; }
        .main-card { box-shadow: none !important; border: none !important; }
        .stat-box-danger, .stat-box-stellar { border: 1px solid #000 !important; }
    }
</style>
@endsection