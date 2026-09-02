@extends('layouts.admin')

@section('content')
<div class="reporte-vivienda-stellar">
    <!-- 1. CABECERA RESPONSIVA -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4 mb-md-5 border-bottom pb-4 gap-3">
        <div>
            <h2 class="text-stellar-blue mb-0 fw-bold"><i class="fas fa-file-invoice me-2"></i> Reporte por Vivienda</h2>
            <p class="text-muted mb-0">Historial consolidado de servicios y consumo medido.</p>
        </div>
        @if($viviendaSeleccionada)
            {{-- BOTÓN ACTUALIZADO PARA DESCARGAR PDF --}}
            <a href="{{ route('admin.reportes.vivienda.descargar', $viviendaSeleccionada->id_vivienda) }}" class="btn btn-stellar-blue px-4 shadow-sm w-100 w-md-auto">
                <i class="fas fa-file-pdf me-2"></i> Descargar Historial PDF
            </a>
        @endif
    </div>

    <!-- 2. BUSCADOR FILTRADO -->
    <div class="card border-0 shadow-sm mb-5 rounded-4 bg-light-soft no-print">
        <div class="card-body p-4">
            <form action="{{ route('admin.reportes.vivienda') }}" method="GET" class="row g-3 align-items-end">
                <div class="col-md-9">
                    <label class="form-label-stellar">Seleccionar Vivienda</label>
                    <select name="id_vivienda" class="form-select select2-stellar" required>
                        <option value="">-- Seleccione una casa para consultar --</option>
                        @foreach($viviendas as $v)
                            <option value="{{ $v->id_vivienda }}" {{ request('id_vivienda') == $v->id_vivienda ? 'selected' : '' }}>
                                Casa #{{ $v->nro_casa }} · Propietario: {{ $v->propietario->nombre ?? 'S/N' }} {{ $v->propietario->apellido_paterno ?? '' }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-stellar-blue w-100 py-2">
                        <i class="fas fa-search me-2"></i>Consultar
                    </button>
                </div>
            </form>
        </div>
    </div>

    @if($viviendaSeleccionada)
    <!-- 3. INFO DE LA PROPIEDAD SELECCIONADA -->
    <div class="propiedad-info-card shadow-sm mb-5 p-4 bg-white rounded-4 border-start border-stellar-blue border-5">
        <div class="row align-items-center">
            <div class="col-md-6">
                <small class="text-uppercase fw-bold text-muted letter-spacing-1">Información de Unidad</small>
                <h3 class="mb-1 fw-bold text-dark">Casa #{{ $viviendaSeleccionada->nro_casa }}</h3>
                <p class="text-muted mb-0">Responsable: <b class="text-stellar-blue">{{ $viviendaSeleccionada->propietario->nombre }} {{ $viviendaSeleccionada->propietario->apellido_paterno }}</b></p>
            </div>
            <div class="col-md-6 text-md-end mt-3 mt-md-0">
                <span class="badge bg-blue-soft text-stellar-blue p-2 px-3 fw-bold rounded-pill">
                    <i class="fas fa-tachometer-alt me-2"></i>Medidor: {{ $viviendaSeleccionada->nro_medidor }}
                </span>
            </div>
        </div>
    </div>

    <!-- 4. GRILLA DE REPORTES DETALLADOS -->
    <div class="row g-4">
        
        <!-- HISTORIAL DE AGUA -->
        <div class="col-md-6">
            <div class="card border-0 shadow-sm rounded-4 h-100 overflow-hidden">
                <div class="card-header bg-white py-3 border-bottom border-info border-opacity-25">
                    <h6 class="mb-0 fw-bold text-info"><i class="fas fa-tint me-2"></i> Consumos de Agua</h6>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-sm table-hover mb-0 align-middle">
                            <thead class="bg-light">
                                <tr>
                                    <th class="ps-3 py-2 uppercase-tracking">Periodo</th>
                                    <th class="uppercase-tracking">Monto</th>
                                    <th class="text-center uppercase-tracking">Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($pagosAgua as $a)
                                <tr>
                                    <td class="ps-3">{{ $a->mes }}/{{ $a->anio }}</td>
                                    <td class="fw-bold">Bs. {{ number_format($a->total_pagar, 2) }}</td>
                                    <td class="text-center">
                                        <span class="badge-stellar {{ $a->estado_pago == 'Pagado' ? 'bg-success-soft text-success' : 'bg-danger-soft text-danger' }}">
                                            {{ $a->estado_pago }}
                                        </span>
                                    </td>
                                </tr>
                                @empty
                                <tr><td colspan="3" class="text-center py-4 text-muted small">Sin registros de agua.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- HISTORIAL DE MANTENIMIENTO -->
        <div class="col-md-6">
            <div class="card border-0 shadow-sm rounded-4 h-100 overflow-hidden">
                <div class="card-header bg-white py-3 border-bottom border-warning border-opacity-25">
                    <h6 class="mb-0 fw-bold text-warning"><i class="fas fa-tools me-2"></i> Mantenimiento Fijo</h6>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-sm table-hover mb-0 align-middle">
                            <thead class="bg-light">
                                <tr>
                                    <th class="ps-3 py-2 uppercase-tracking">Periodo</th>
                                    <th class="uppercase-tracking">Monto</th>
                                    <th class="text-center uppercase-tracking">Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($pagosMante as $m)
                                <tr>
                                    <td class="ps-3">{{ $m->mes }}/{{ $m->anio }}</td>
                                    <td class="fw-bold">Bs. {{ number_format($m->monto_fijo, 2) }}</td>
                                    <td class="text-center">
                                        <span class="badge-stellar {{ $m->estado_pago == 'Pagado' ? 'bg-success-soft text-success' : 'bg-danger-soft text-danger' }}">
                                            {{ $m->estado_pago }}
                                        </span>
                                    </td>
                                </tr>
                                @empty
                                <tr><td colspan="3" class="text-center py-4 text-muted small">Sin registros de mantenimiento.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- HISTORIAL DE EXPENSAS / REMESAS -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-4 h-100 overflow-hidden">
                <div class="card-header bg-white py-3 border-bottom">
                    <h6 class="mb-0 fw-bold text-stellar-blue"><i class="fas fa-hand-holding-usd me-2"></i> Detalle de Expensas y Remesas</h6>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-sm table-hover mb-0 align-middle">
                            <thead class="bg-light">
                                <tr>
                                    <th class="ps-3 py-2 uppercase-tracking">Concepto</th>
                                    <th class="uppercase-tracking">Periodo</th>
                                    <th class="uppercase-tracking">Monto</th>
                                    <th class="text-center uppercase-tracking">Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($pagosRemesas as $r)
                                <tr>
                                    <td class="ps-3"><b>{{ $r->configuracion->nombre_remesa ?? 'Expensa Extra' }}</b></td>
                                    <td>{{ $r->mes }}/{{ $r->anio }}</td>
                                    <td class="fw-bold">Bs. {{ number_format($r->monto_pactado, 2) }}</td>
                                    <td class="text-center">
                                        <span class="badge-stellar {{ $r->estado_pago == 'Pagado' ? 'bg-success-soft text-success' : 'bg-danger-soft text-danger' }}">
                                            {{ $r->estado_pago }}
                                        </span>
                                    </td>
                                </tr>
                                @empty
                                <tr><td colspan="4" class="text-center py-4 text-muted small">Sin registros de remesas.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- ÚLTIMAS LECTURAS REGISTRADAS -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm rounded-4 h-100 border-top border-info border-5">
                <div class="card-header bg-white py-3">
                    <h6 class="mb-0 fw-bold text-dark"><i class="fas fa-chart-line me-2"></i> Consumo Reciente (m³)</h6>
                </div>
                <div class="card-body p-0">
                    <table class="table table-sm mb-0">
                        <tbody>
                            @foreach($historialLecturas->take(6) as $l)
                            <tr>
                                <td class="ps-3 py-2 text-muted">Mes {{ $l->periodo_mes }} / {{ $l->periodo_anio }}</td>
                                <td class="text-end fw-bold text-info pe-3">{{ number_format($l->consumo_m3, 2) }} m³</td>
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
    /* VARIABLES STELLAR GREEN/BLUE */
    :root {
        --stellar-blue: #0e5cad;
        --stellar-button: linear-gradient(45deg, #22349e 0%, #8183e6 100%);
    }

    .text-stellar-blue { color: var(--stellar-blue); }
    .bg-blue-soft { background-color: rgba(14, 92, 173, 0.08); }
    .bg-success-soft { background-color: rgba(40, 167, 69, 0.1); }
    .bg-danger-soft { background-color: rgba(220, 53, 69, 0.1); }
    .bg-light-soft { background-color: #f8f9fa; }
    
    .uppercase-tracking { font-size: 0.65rem; text-transform: uppercase; letter-spacing: 1.2px; font-weight: 700; color: #888; }
    .letter-spacing-1 { letter-spacing: 1px; }

    /* Estilo del Badge Redondeado */
    .badge-stellar {
        display: inline-block;
        padding: 4px 10px;
        border-radius: 50px;
        font-size: 10px;
        font-weight: 800;
        text-transform: uppercase;
    }

    /* Input y Labels */
    .form-label-stellar { font-size: 0.75rem; text-transform: uppercase; letter-spacing: 1px; font-weight: 700; color: #777; margin-bottom: 8px; display: block; }

    /* Botón Stellar Blue */
    .btn-stellar-blue { 
        background: var(--stellar-button); 
        color: white !important; 
        border-radius: 50px; 
        font-weight: bold; 
        border: none; 
        padding: 10px 25px;
        transition: 0.3s; 
        text-transform: uppercase;
        font-size: 0.8rem;
    }
    .btn-stellar-blue:hover { transform: translateY(-2px); box-shadow: 0 5px 15px rgba(34, 52, 158, 0.3); }

    .rounded-4 { border-radius: 1.25rem !important; }

    /* Ajuste para impresión manual (por si acaso) */
    @media print {
        .no-print { display: none !important; }
        .main-card { box-shadow: none !important; border: none !important; padding: 0 !important; }
    }
</style>
@endsection