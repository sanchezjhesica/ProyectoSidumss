@extends('layouts.admin')

@section('content')
<div class="tarifas-stellar">
    <!-- Cabecera de Página -->
    <div class="d-flex justify-content-between align-items-center mb-5 border-bottom pb-4">
        <div>
            <h2 class="text-purple mb-0"><i class="fas fa-sliders-h me-2"></i> Configuración de Tarifas</h2>
            <p class="text-muted mb-0">Defina los parámetros económicos que rigen los cálculos de cobro del sistema.</p>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-stellar alert-dismissible fade show mb-4" role="alert">
            <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-5">
            <form action="{{ route('admin.tarifas.update') }}" method="POST">
                @csrf
                
                <div class="row g-4">
                    <!-- Precio Agua -->
                    <div class="col-md-6">
                        <div class="form-group-stellar">
                            <label class="form-label fw-bold text-dark"><i class="fas fa-tint text-info me-2"></i>Precio por m³ de Agua</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white border-end-0">Bs.</span>
                                <input type="number" step="0.01" name="precio_por_m3_agua" class="form-control form-stellar border-start-0" value="{{ $tarifa->precio_por_m3_agua ?? '' }}" required>
                            </div>
                            <small class="text-muted">Costo variable según el consumo medido.</small>
                        </div>
                    </div>

                    <!-- Mantenimiento -->
                    <div class="col-md-6">
                        <div class="form-group-stellar">
                            <label class="form-label fw-bold text-dark"><i class="fas fa-tools text-secondary me-2"></i>Cuota Fija Mantenimiento</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white border-end-0">Bs.</span>
                                <input type="number" step="0.01" name="monto_fijo_mantenimiento" class="form-control form-stellar border-start-0" value="{{ $tarifa->monto_fijo_mantenimiento ?? '' }}" required>
                            </div>
                            <small class="text-muted">Aporte mensual para gastos administrativos.</small>
                        </div>
                    </div>

                    <!-- Alcantarillado -->
                    <div class="col-md-6">
                        <div class="form-group-stellar">
                            <label class="form-label fw-bold text-dark"><i class="fas fa-faucet-drip text-primary me-2"></i>Monto Alcantarillado</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white border-end-0">Bs.</span>
                                <input type="number" step="0.01" name="monto_alcantarillado" class="form-control form-stellar border-start-0" value="{{ $tarifa->monto_alcantarillado ?? '' }}" required>
                            </div>
                            <small class="text-muted">Costo fijo por servicio de red sanitaria.</small>
                        </div>
                    </div>

                    <!-- Mora -->
                    <div class="col-md-6">
                        <div class="form-group-stellar">
                            <label class="form-label fw-bold text-dark"><i class="fas fa-percentage text-danger me-2"></i>Porcentaje de Mora</label>
                            <div class="input-group">
                                <input type="number" step="0.01" name="porcentaje_mora" class="form-control form-stellar border-end-0" value="{{ $tarifa->porcentaje_mora ?? '' }}" required>
                                <span class="input-group-text bg-white border-start-0">%</span>
                            </div>
                            <small class="text-muted">Penalización aplicada sobre deudas pendientes.</small>
                        </div>
                    </div>
                </div>

                <div class="mt-5 d-flex gap-3 border-top pt-4">
                    <button type="submit" class="btn btn-purple-stellar px-5 py-2">
                        <i class="fas fa-save me-2"></i> Guardar Configuración
                    </button>
                    <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary rounded-pill px-4 py-2">
                        Cancelar
                    </a>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Nota informativa -->
    <div class="mt-4 p-4 bg-stellar-soft border-start border-purple border-4 rounded-3 shadow-sm">
        <div class="d-flex align-items-center">
            <i class="fas fa-info-circle text-purple fs-4 me-3"></i>
            <div>
                <strong class="text-purple">Aviso Importante:</strong>
                <p class="mb-0 small text-muted">Los cambios realizados en las tarifas afectarán únicamente a los <strong>nuevos cobros</strong> generados a partir de este momento. Los registros históricos y facturas ya emitidas no sufrirán modificaciones.</p>
            </div>
        </div>
    </div>
</div>

<style>
    .text-purple { color: #5f4d93; }
    .border-purple { border-color: #5f4d93 !important; }
    .bg-stellar-soft { background-color: rgba(95, 77, 147, 0.04); }

    .form-stellar {
        border: 1px solid #ddd;
        border-radius: 8px;
        padding: 12px;
        font-weight: 500;
    }

    .form-stellar:focus {
        border-color: #5f4d93;
        box-shadow: 0 0 0 0.25rem rgba(95, 77, 147, 0.1);
    }

    .input-group-text {
        border: 1px solid #ddd;
        color: #888;
        font-weight: bold;
    }

    /* Botón Principal */
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
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(95, 77, 147, 0.3);
    }

    /* Alerta */
    .alert-stellar {
        background: #fff;
        border-left: 5px solid #5f4d93;
        color: #5f4d93;
        box-shadow: 0 4px 12px rgba(0,0,0,0.05);
    }

    .rounded-4 { border-radius: 1rem !important; }
</style>
@endsection