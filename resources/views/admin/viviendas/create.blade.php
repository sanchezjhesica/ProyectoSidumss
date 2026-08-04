@extends('layouts.admin')

@section('content')
<div class="viviendas-create-stellar">
    <!-- Cabecera del Formulario -->
    <div class="d-flex justify-content-between align-items-center mb-5 border-bottom pb-4">
        <div>
            <h2 class="text-purple mb-0"><i class="fas fa-home me-2"></i> Registrar Nueva Vivienda</h2>
            <p class="text-muted mb-0">Añada una nueva propiedad al sistema para el control de lecturas y facturación.</p>
        </div>
        <a href="{{ route('admin.viviendas.index') }}" class="btn btn-outline-secondary rounded-pill px-4">
            <i class="fas fa-arrow-left me-2"></i> Volver
        </a>
    </div>

    <!-- Tarjeta del Formulario -->
    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-5">
            <form action="{{ route('admin.viviendas.store') }}" method="POST">
                @csrf
                
                <h5 class="text-purple fw-bold mb-4 border-bottom pb-2">Identificación de Propiedad</h5>
                <div class="row g-4 mb-5">
                    <div class="col-md-6">
                        <label class="form-label fw-bold small text-uppercase tracking-wider">
                            <i class="fas fa-hashtag text-muted me-1"></i> Número de Casa
                        </label>
                        <input type="text" name="nro_casa" class="form-control form-stellar" placeholder="Ej: 10-A" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold small text-uppercase tracking-wider">
                            <i class="fas fa-tachometer-alt text-muted me-1"></i> Número de Medidor
                        </label>
                        <input type="text" name="nro_medidor" class="form-control form-stellar" placeholder="Ej: MED-001" required>
                    </div>
                </div>

                <h5 class="text-purple fw-bold mb-4 border-bottom pb-2">Ubicación y Clasificación</h5>
                <div class="row g-4 mb-5">
                    <div class="col-md-8">
                        <label class="form-label fw-bold small text-uppercase tracking-wider">
                            <i class="fas fa-map-marker-alt text-muted me-1"></i> Calle / Dirección
                        </label>
                        <input type="text" name="calle" class="form-control form-stellar" placeholder="Nombre de la calle o pasaje">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-bold small text-uppercase tracking-wider">
                            <i class="fas fa-layer-group text-muted me-1"></i> Tipo de Vivienda
                        </label>
                        <select name="tipo_vivienda" class="form-select form-stellar">
                            <option value="Casa">Casa Habitada</option>
                            <option value="Lote Vacio">Lote Baldío / Vacío</option>
                        </select>
                    </div>
                </div>

                <div class="pt-4 border-top">
                    <button type="submit" class="btn btn-purple-stellar btn-lg px-5 shadow-sm">
                        <i class="fas fa-save me-2"></i> Guardar Vivienda
                    </button>
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

    /* Botón con Degradado */
    .btn-purple-stellar {
        background: linear-gradient(45deg, #5f4d93 0%, #e37682 100%);
        color: white;
        border-radius: 50px;
        font-weight: bold;
        border: none;
        transition: 0.3s;
    }
    .btn-purple-stellar:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(95, 77, 147, 0.4) !important;
        color: white;
    }

    .rounded-4 { border-radius: 1.25rem !important; }
</style>
@endsection