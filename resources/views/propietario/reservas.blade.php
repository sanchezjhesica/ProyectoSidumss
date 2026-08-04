@extends('layouts.propietario')

@section('content')
<div class="reservas-stellar">
    <!-- 1. CABECERA DE SECCIÓN -->
    <div class="d-flex justify-content-between align-items-center mb-5 border-bottom pb-4">
        <div>
            <h2 class="text-purple mb-0"><i class="fas fa-calendar-check me-2"></i> Reservar Áreas Recreativas</h2>
            <p class="text-muted mb-0">Solicite el uso del Salón de Eventos o la cancha de Wally de la urbanización.</p>
        </div>
    </div>

    <div class="row g-4">
        <!-- 2. FORMULARIO DE NUEVA RESERVA -->
        <div class="col-md-5">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-header bg-white py-3 border-bottom">
                    <h5 class="mb-0 fw-bold text-purple"><i class="fas fa-plus-circle me-2"></i> Nueva Solicitud</h5>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('propietario.reservas.store') }}" method="POST">
                        @csrf
                        
                        <div class="mb-4">
                            <label class="form-label fw-bold small text-uppercase tracking-wider">¿Qué área desea reservar?</label>
                            <select name="id_area" class="form-select form-stellar py-2" required>
                                <option value="" selected disabled>-- Seleccione un espacio --</option>
                                @foreach($areas as $area)
                                    <option value="{{ $area->id_area }}">
                                        {{ $area->nombre_area }} (Bs. {{ number_format($area->costo_estandar, 2) }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-5">
                            <label class="form-label fw-bold small text-uppercase tracking-wider">Fecha del Evento</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="far fa-calendar-alt text-muted"></i></span>
                                <input type="date" name="fecha" class="form-control form-stellar border-start-0 py-2" value="{{ date('Y-m-d') }}" required>
                            </div>
                            <small class="text-muted mt-2 d-block italic"><i class="fas fa-info-circle me-1"></i> El costo se cargará automáticamente a su próximo aviso.</small>
                        </div>

                        <button type="submit" class="btn btn-purple-stellar btn-lg w-100 shadow-sm">
                            <i class="fas fa-check-circle me-2"></i> Confirmar Mi Reserva
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- 3. LISTADO DE MIS RESERVAS -->
        <div class="col-md-7">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden h-100">
                <div class="card-header bg-white py-3 border-bottom">
                    <h5 class="mb-0 fw-bold text-dark"><i class="fas fa-history me-2"></i> Mis Reservas Realizadas</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="ps-4 py-3 uppercase-tracking">Fecha</th>
                                    <th class="py-3 uppercase-tracking">Espacio</th>
                                    <th class="py-3 uppercase-tracking">Costo</th>
                                    <th class="py-3 text-center uppercase-tracking">Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($reservas as $r)
                                <tr>
                                    <td class="ps-4">
                                        <div class="fw-bold text-dark small">
                                            {{ \Carbon\Carbon::parse($r->fecha_reserva)->format('d/m/Y') }}
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-purple-soft text-purple px-2">
                                            <i class="fas fa-map-marker-alt me-1"></i> 
                                            {{ $r->area->nombre_area ?? 'Reserva' }}
                                        </span>
                                    </td>
                                    <td class="fw-bold">Bs. {{ number_format($r->costo_pactado, 2) }}</td>
                                    <td class="text-center">
                                        @if($r->estado_pago == 'Pagado')
                                            <span class="badge-stellar bg-success-soft text-success">Pagado</span>
                                        @else
                                            <span class="badge-stellar bg-warning-soft text-warning">Pendiente</span>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center py-5 text-muted">No ha realizado ninguna reserva todavía.</td>
                                </tr>
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
    /* VARIABLES STELLAR */
    .text-purple { color: #5f4d93; }
    .bg-purple-soft { background-color: rgba(95, 77, 147, 0.08); }
    .bg-success-soft { background-color: rgba(40, 167, 69, 0.1); }
    .bg-warning-soft { background-color: rgba(255, 193, 7, 0.1); }
    
    .uppercase-tracking {
        font-size: 0.7rem;
        text-transform: uppercase;
        letter-spacing: 1.5px;
        font-weight: 700;
        color: #888;
    }

    /* Estilo de Inputs */
    .form-stellar {
        border: 1px solid #e0e0e0;
        border-radius: 10px;
        transition: all 0.3s;
    }
    .form-stellar:focus {
        border-color: #5f4d93;
        box-shadow: 0 0 0 0.25rem rgba(95, 77, 147, 0.1);
    }

    /* Badges Pill */
    .badge-stellar {
        display: inline-block;
        padding: 5px 12px;
        border-radius: 50px;
        font-size: 10px;
        font-weight: 700;
        text-transform: uppercase;
    }

    /* Botón Stellar Principal */
    .btn-purple-stellar {
        background: linear-gradient(45deg, #5f4d93 0%, #e37682 100%);
        color: white;
        border-radius: 50px;
        font-weight: bold;
        border: none;
        padding: 12px;
        transition: 0.3s;
    }
    .btn-purple-stellar:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(95, 77, 147, 0.3);
        color: white;
    }

    .rounded-4 { border-radius: 1rem !important; }
    .italic { font-style: italic; }
</style>
@endsection