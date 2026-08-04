@extends('layouts.admin')

@section('content')
<div class="viviendas-edit-stellar">
    <!-- Cabecera de Edición -->
    <div class="d-flex justify-content-between align-items-center mb-5 border-bottom pb-4">
        <div>
            <h2 class="text-purple mb-0"><i class="fas fa-edit me-2"></i> Editar Vivienda: {{ $vivienda->nro_casa }}</h2>
            <p class="text-muted mb-0">Modifique los datos técnicos o la ubicación de la propiedad seleccionada.</p>
        </div>
        <a href="{{ route('admin.viviendas.index') }}" class="btn btn-outline-secondary rounded-pill px-4">
            <i class="fas fa-times me-2"></i> Cancelar
        </a>
    </div>

    <!-- Tarjeta del Formulario -->
    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-5">
            <form action="{{ route('admin.viviendas.update', $vivienda->id_vivienda) }}" method="POST">
                @csrf
                @method('PUT')
                
                <h5 class="text-purple fw-bold mb-4 border-bottom pb-2">Datos de Identificación</h5>
                <div class="row g-4 mb-5">
                    <div class="col-md-6">
                        <label class="form-label fw-bold small text-uppercase tracking-wider">
                            <i class="fas fa-hashtag text-muted me-1"></i> Número de Casa
                        </label>
                        <input type="text" name="nro_casa" class="form-control form-stellar" value="{{ $vivienda->nro_casa }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold small text-uppercase tracking-wider">
                            <i class="fas fa-tachometer-alt text-muted me-1"></i> Número de Medidor
                        </label>
                        <input type="text" name="nro_medidor" class="form-control form-stellar" value="{{ $vivienda->nro_medidor }}" required>
                    </div>
                </div>

                <h5 class="text-purple fw-bold mb-4 border-bottom pb-2">Localización y Estado</h5>
                <div class="row g-4 mb-5">
                    <div class="col-md-8">
                        <label class="form-label fw-bold small text-uppercase tracking-wider">
                            <i class="fas fa-map-marker-alt text-muted me-1"></i> Calle / Dirección
                        </label>
                        <input type="text" name="calle" class="form-control form-stellar" value="{{ $vivienda->calle }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-bold small text-uppercase tracking-wider">
                            <i class="fas fa-info-circle text-muted me-1"></i> Tipo de Vivienda
                        </label>
                        <select name="tipo_vivienda" class="form-select form-stellar">
                            <option value="Casa" {{ $vivienda->tipo_vivienda == 'Casa' ? 'selected' : '' }}>Casa Habitada</option>
                            <option value="Lote Vacio" {{ $vivienda->tipo_vivienda == 'Lote Vacio' ? 'selected' : '' }}>Lote Baldío / Vacío</option>
                        </select>
                    </div>
                </div>

                <div class="pt-4 border-top d-flex gap-3">
                    <button type="submit" class="btn btn-purple-stellar px-5 shadow-sm">
                        <i class="fas fa-sync-alt me-2"></i> Actualizar Vivienda
                    </button>
                    <a href="{{ route('admin.viviendas.index') }}" class="btn btn-light rounded-pill px-4">
                        Descartar Cambios
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    .text-purple { color: #5f4d93; }
    .tracking-wider { letter-spacing: 1px; color: #777; }

    /* Estilo de los inputs Stellar */
    .form-stellar {
        border: 1px solid #e0e0e0;
        border-radius: 10px;
        padding: 12px 15px;
        background-color: #fcfcfc;
        transition: all 0.3s ease;
    }
    .form-stellar:focus {
        border-color: #5f4d93;
        background-color: #fff;
        box-shadow: 0 0 0 0.25rem rgba(95, 77, 147, 0.1);
    }

    /* Botón con Degradado Stellar */
    .btn-purple-stellar {
        background: linear-gradient(45deg, #5f4d93 0%, #e37682 100%);
        color: white;
        border-radius: 50px;
        font-weight: bold;
        border: none;
        padding: 12px 30px;
        transition: 0.3s;
    }
    .btn-purple-stellar:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(95, 77, 147, 0.4);
        color: white;
    }

    .rounded-4 { border-radius: 1.25rem !important; }
</style>
@endsection