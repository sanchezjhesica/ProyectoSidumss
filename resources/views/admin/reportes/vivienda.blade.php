@extends('layouts.admin')

@section('content')
<div class="reporte-vivienda-stellar">
    <!-- Cabecera -->
    <div class="d-flex justify-content-between align-items-center mb-5 border-bottom pb-4">
        <div>
            <h2 class="text-purple mb-0"><i class="fas fa-search-location me-2"></i> Estado de Cuenta por Vivienda</h2>
            <p class="text-muted mb-0">Consulte el historial de pagos y consumos detallados de una propiedad específica.</p>
        </div>
        @if($viviendaSeleccionada)
        <div class="no-print">
            <button onclick="window.print()" class="btn btn-purple-stellar px-4">
                <i class="fas fa-print me-2"></i> Imprimir Reporte
            </button>
        </div>
        @endif
    </div>

    <!-- Buscador Estilizado -->
    <div class="card border-0 shadow-sm mb-5 rounded-4">
        <div class="card-body p-4">
            <form action="{{ route('admin.reportes.vivienda') }}" method="GET" class="row g-3 align-items-end">
                <div class="col-md-9">
                    <label class="form-label fw-bold text-dark"><i class="fas fa-home me-2 text-purple"></i>Seleccionar Vivienda / Casa</label>
                    <select name="id_vivienda" class="form-select form-stellar" required>
                        <option value="">-- Seleccione una casa para analizar --</option>
                        @foreach($viviendas as $v)
                            <option value="{{ $v->id_vivienda }}" {{ request('id_vivienda') == $v->id_vivienda ? 'selected' : '' }}>
                                Casa {{ $v->nro_casa }} · Medidor: {{ $v->nro_medidor }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-purple-stellar w-100 py-2">
                        <i class="fas fa-sync-alt me-2"></i> Generar Reporte
                    </button>
                </div>
            </form>
        </div>
    </div>

    @if($viviendaSeleccionada)
    <!-- Resumen de la Vivienda -->
    <div class="propiedad-info-card shadow-sm mb-5 d-flex align-items-center p-4">
        <div class="icon-circle-purple me-4">
            <i class="fas fa-map-marked-alt"></i>
        </div>
        <div>
            <h4 class="mb-1 fw-bold text-dark">Propiedad: Casa #{{ $viviendaSeleccionada->nro_casa }}</h4>
            <p class="text-muted mb-0">
                <i class="fas fa-user-circle me-1"></i> Propietario: 
                <span class="fw-bold text-purple">{{ $viviendaSeleccionada->propietarios->first()->nombre ?? 'Sin Asignar' }}</span>
            </p>
        </div>
    </div>

    <div class="row">
        <!-- TABLA PAGOS -->
        <div class="col-md-6">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-bold text-purple"><i class="fas fa-money-check-alt me-2"></i> Historial de Pagos</h5>
                </div>
                <div class="card-body p-0">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-4 uppercase-tracking">Periodo</th>
                                <th class="uppercase-tracking">Monto</th>
                                <th class="text-center uppercase-tracking">Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($historialPagos as $p)
                            <tr>
                                <td class="ps-4 fw-bold">{{ $p->periodo_mes }}/{{ $p->periodo_anio }}</td>
                                <td>Bs. {{ number_format($p->total_pagar, 2) }}</td>
                                <td class="text-center">
                                    <span class="badge rounded-pill {{ $p->estado_pago == 'Pagado' ? 'bg-success-soft text-success' : 'bg-danger-soft text-danger' }} px-3 py-2">
                                        {{ $p->estado_pago }}
                                    </span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- TABLA CONSUMO -->
        <div class="col-md-6">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-bold text-info"><i class="fas fa-faucet me-2"></i> Consumo de Agua</h5>
                </div>
                <div class="card-body p-0">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-4 uppercase-tracking">Fecha</th>
                                <th class="uppercase-tracking text-center">m³</th>
                                <th class="uppercase-tracking">Lecturas (Ant/Act)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($historialLecturas as $l)
                            <tr>
                                <td class="ps-4">{{ \Carbon\Carbon::parse($l->fecha_lectura)->format('d/m/y') }}</td>
                                <td class="text-center">
                                    <span class="fw-bold text-info fs-6">{{ $l->lectura_actual - $l->lectura_anterior }}</span>
                                </td>
                                <td class="small text-muted">
                                    {{ number_format($l->lectura_anterior, 1) }} → {{ number_format($l->lectura_actual, 1) }}
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>

<style>
    .text-purple { color: #5f4d93; }
    .bg-success-soft { background-color: rgba(40, 167, 69, 0.1); }
    .bg-danger-soft { background-color: rgba(220, 53, 69, 0.1); }
    
    .uppercase-tracking {
        font-size: 0.7rem;
        text-transform: uppercase;
        letter-spacing: 1px;
        font-weight: 700;
        color: #888;
    }

    .form-stellar {
        border: 1px solid #e0e0e0;
        border-radius: 8px;
        padding: 10px;
    }

    .propiedad-info-card {
        background: #fff;
        border-radius: 15px;
        border-left: 5px solid #5f4d93;
    }

    .icon-circle-purple {
        width: 60px; height: 60px;
        background: rgba(95, 77, 147, 0.1);
        color: #5f4d93;
        border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.5rem;
    }

    .btn-purple-stellar {
        background: #5f4d93;
        color: white;
        border-radius: 50px;
        font-weight: bold;
        border: none;
        transition: 0.3s;
    }
    .btn-purple-stellar:hover {
        background: #4a3b75;
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(95, 77, 147, 0.3);
    }

    .rounded-4 { border-radius: 1rem !important; }

    @media print {
        .no-print, nav, #header-stellar { display: none !important; }
        body { background: white !important; }
        .main-wrapper { max-width: 100% !important; margin: 0 !important; padding: 0 !important; }
        .main-card { box-shadow: none !important; border: none !important; padding: 0 !important; }
        .propiedad-info-card { border: 1px solid #000 !important; }
        .table { font-size: 12px; }
    }
</style>
@endsection