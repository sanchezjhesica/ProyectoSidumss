@extends('layouts.propietario')

@section('content')
<div class="reservas-stellar">
    <!-- 1. CABECERA DE SECCIÓN -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4 mb-md-5 border-bottom pb-4 gap-3">
        <div>
            <h2 class="text-stellar-blue mb-0 fw-bold"><i class="fas fa-calendar-check me-2"></i> Reservar Áreas Recreativas</h2>
            <p class="text-muted mb-0">Solicite el uso del Salón de Eventos o canchas deportivas.</p>
        </div>
        <div class="badge bg-blue-soft text-stellar-blue p-2 rounded-pill px-3">
            <i class="fas fa-info-circle me-1"></i> Costo cargado al aviso de Agua
        </div>
    </div>

    <!-- 2. MENSAJES DE ÉXITO -->
    @if(session('success'))
        <div class="alert alert-stellar alert-dismissible fade show mb-4" role="alert">
            <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row g-4">
        <!-- 3. FORMULARIO DE RESERVA -->
        <div class="col-lg-5">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-header bg-white py-3 border-bottom">
                    <h5 class="mb-0 fw-bold text-stellar-blue">Nueva Solicitud</h5>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('propietario.reservas.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label-stellar">Área Recreativa</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="fas fa-map-marker-alt text-muted"></i></span>
                                <select name="id_area" class="form-select form-stellar" required>
                                    <option value="" disabled selected>Seleccione un espacio...</option>
                                    @foreach($areas as $area)
                                        <option value="{{ $area->id_area }}">
                                            {{ $area->nombre_area }} — Bs. {{ number_format($area->costo_reserva, 2) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="mb-4">
                            <label class="form-label-stellar">Fecha del Evento</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="fas fa-calendar-day text-muted"></i></span>
                                <input type="date" name="fecha" class="form-control form-stellar" min="{{ date('Y-m-d') }}" required>
                            </div>
                            <small class="text-muted mt-2 d-block">Solo se permiten reservas desde la fecha actual.</small>
                        </div>
                        <button type="submit" class="btn btn-stellar-submit w-100 py-3 shadow-sm">
                            <i class="fas fa-paper-plane me-2"></i> Confirmar Mi Reserva
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- 4. LISTADO DE MIS RESERVAS -->
        <div class="col-lg-7">
            <div class="card border-0 shadow-sm rounded-4 h-100 overflow-hidden">
                <div class="card-header bg-white py-3 border-bottom">
                    <h5 class="mb-0 fw-bold text-dark">Historial de Reservas</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="ps-4 py-3 uppercase-tracking">Fecha</th>
                                    <th class="py-3 uppercase-tracking">Espacio</th>
                                    <th class="py-3 uppercase-tracking">Costo</th>
                                    <th class="py-3 text-center uppercase-tracking">Estado Pago</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($reservas as $r)
                                <tr>
                                    <td class="ps-4">
                                        <span class="fw-bold">{{ \Carbon\Carbon::parse($r->fecha_reserva)->format('d/m/Y') }}</span>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="icon-circle me-2"><i class="fas fa-star text-stellar-blue"></i></div>
                                            {{ $r->nombre_area }}
                                        </div>
                                    </td>
                                    <td><span class="text-dark fw-bold">Bs. {{ number_format($r->costo_pactado, 2) }}</span></td>
                                    <td class="text-center">
                                        <span class="badge-stellar {{ $r->estado_pago == 'Pagado' ? 'bg-success-soft text-success' : 'bg-warning-soft text-warning-dark' }}">
                                            {{ $r->estado_pago }}
                                        </span>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center py-5 text-muted">
                                        <i class="fas fa-calendar-times fa-2x mb-3 d-block opacity-25"></i>
                                        Usted no ha realizado reservas aún.
                                    </td>
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
    /* VARIABLES STELLAR GREEN/BLUE */
    :root {
        --stellar-blue: #0e5cad;
        --stellar-button: linear-gradient(45deg, #22349e 0%, #8183e6 100%);
    }

    .text-stellar-blue { color: var(--stellar-blue); }
    .bg-blue-soft { background-color: rgba(14, 92, 173, 0.08); }
    .bg-success-soft { background-color: rgba(40, 167, 69, 0.1); }
    .bg-warning-soft { background-color: rgba(255, 193, 7, 0.15); }
    .text-warning-dark { color: #856404; }

    .uppercase-tracking {
        font-size: 0.7rem;
        text-transform: uppercase;
        letter-spacing: 1.2px;
        font-weight: 700;
        color: #888;
    }

    /* Formulario */
    .form-label-stellar {
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 1px;
        font-weight: 700;
        color: #777;
        margin-bottom: 8px;
    }

    .form-stellar {
        border: 1px solid #e0e0e0;
        border-radius: 10px;
        padding: 10px 15px;
        transition: 0.3s;
    }
    .form-stellar:focus {
        border-color: var(--stellar-blue);
        box-shadow: 0 0 0 0.25rem rgba(14, 92, 173, 0.1);
    }

    /* Botón Submit */
    .btn-stellar-submit {
        background: var(--stellar-button);
        color: white !important;
        border-radius: 50px;
        font-weight: 700;
        border: none;
        transition: 0.3s;
        text-transform: uppercase;
        letter-spacing: 1px;
    }
    .btn-stellar-submit:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(34, 52, 158, 0.3);
    }

    /* Alertas */
    .alert-stellar {
        background: #fff;
        border-left: 5px solid var(--stellar-blue);
        color: var(--stellar-blue);
        box-shadow: 0 4px 12px rgba(0,0,0,0.05);
    }

    /* Badges y Decoración */
    .badge-stellar {
        padding: 6px 15px;
        border-radius: 50px;
        font-size: 10px;
        font-weight: 800;
        text-transform: uppercase;
    }

    .icon-circle {
        width: 30px; height: 30px;
        background: rgba(14, 92, 173, 0.05);
        border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
    }

    .rounded-4 { border-radius: 1.25rem !important; }
</style>
@endsection