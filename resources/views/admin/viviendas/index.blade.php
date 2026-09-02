@extends('layouts.admin')

@section('content')
<div class="viviendas-stellar">
    <!-- 1. ENCABEZADO RESPONSIVO -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4 mb-md-5 border-bottom pb-4 gap-3">
        <div>
            <h2 class="text-stellar-blue mb-0 fw-bold"><i class="fas fa-city me-2"></i> Designación de Propietarios</h2>
            <p class="text-muted mb-0">Asigne y gestione los responsables de cada unidad habitacional.</p>
        </div>
    </div>

    <!-- 2. ALERTA DE ÉXITO -->
    @if(session('success'))
        <div class="alert alert-stellar alert-dismissible fade show mb-4 shadow-sm" role="alert">
            <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- 3. TABLA DE PROPIEDADES -->
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4 py-3 uppercase-tracking">Vivienda / Casa</th>
                            <th class="py-3 uppercase-tracking">Medidor</th>
                            <th class="py-3 uppercase-tracking">Responsable de Pago</th>
                            <th class="py-3 uppercase-tracking d-none d-md-table-cell">Tipo</th>
                            <th class="py-3 text-center uppercase-tracking">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($viviendas as $v)
                        <tr>
                            <td class="ps-4">
                                <div class="d-flex align-items-center">
                                    <!-- Icono con Degradado -->
                                    <div class="property-icon-stellar me-3">
                                        <i class="fas fa-home"></i>
                                    </div>
                                    <div>
                                        <span class="fw-bold text-dark fs-6">Casa #{{ $v->nro_casa }}</span>
                                        <div class="small text-muted d-md-none">{{ $v->tipo_vivienda }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="badge-stellar bg-blue-soft text-stellar-blue">
                                    {{ $v->nro_medidor }}
                                </span>
                            </td>
                            <td>
                                @if($v->propietario)
                                    <div class="d-flex flex-column">
                                        <span class="fw-bold text-dark">{{ $v->propietario->nombre }} {{ $v->propietario->apellido_paterno }}</span>
                                        <small class="text-muted">CI: {{ $v->propietario->ci }}</small>
                                    </div>
                                @else
                                    <span class="badge-stellar bg-danger-soft text-danger">
                                        <i class="fas fa-user-slash me-1 small"></i> Sin Propietario
                                    </span>
                                @endif
                            </td>
                            <td class="d-none d-md-table-cell">
                                <span class="text-muted small fw-bold text-uppercase">{{ $v->tipo_vivienda }}</span>
                            </td>
                            <td class="text-center pe-3">
                                <div class="d-flex justify-content-center">
                                    <!-- Botón Designar Estilo Pill -->
                                    <a href="{{ route('admin.viviendas.edit', $v->id_vivienda) }}" class="btn btn-sm btn-designar-stellar shadow-sm">
                                        <i class="fas fa-user-tag me-1"></i> <span class="d-none d-lg-inline">Gestionar Dueño</span>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<style>
    /* VARIABLES UNIFICADAS */
    :root {
        --stellar-blue: #0e5cad;
        --stellar-grad: linear-gradient(45deg, #79f1a4 15%, #0e5cad 85%);
        --stellar-button: linear-gradient(45deg, #22349e 0%, #8183e6 100%);
    }

    .text-stellar-blue { color: var(--stellar-blue); }
    .bg-blue-soft { background-color: rgba(14, 92, 173, 0.08); }
    .bg-danger-soft { background-color: rgba(220, 53, 69, 0.1); }
    
    .uppercase-tracking {
        font-size: 0.65rem;
        text-transform: uppercase;
        letter-spacing: 1.2px;
        font-weight: 700;
        color: #888;
    }

    /* Icono de Propiedad con Degradado */
    .property-icon-stellar {
        width: 38px;
        height: 38px;
        background: var(--stellar-grad);
        color: white;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1rem;
        box-shadow: 0 4px 10px rgba(121, 241, 164, 0.3);
    }

    /* Badges Estilo Stellar */
    .badge-stellar {
        display: inline-block;
        padding: 6px 12px;
        border-radius: 50px;
        font-size: 11px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    /* Botón Designar / Editar */
    .btn-designar-stellar {
        color: var(--stellar-blue);
        border: 1px solid var(--stellar-blue);
        background-color: white;
        border-radius: 50px;
        padding: 6px 18px;
        font-weight: 700;
        font-size: 0.75rem;
        text-transform: uppercase;
        transition: 0.3s;
    }
    .btn-designar-stellar:hover {
        background: var(--stellar-blue);
        color: white;
        transform: translateY(-2px);
    }

    /* Alerta Personalizada */
    .alert-stellar {
        background: #fff;
        border-left: 5px solid var(--stellar-blue);
        color: var(--stellar-blue);
        border-radius: 8px;
    }

    .rounded-4 { border-radius: 1.25rem !important; }

    /* Ajustes móviles */
    @media (max-width: 768px) {
        .property-icon-stellar { width: 32px; height: 32px; font-size: 0.85rem; }
        .btn-designar-stellar { padding: 8px; width: 35px; height: 35px; border-radius: 50%; display: flex; align-items: center; justify-content: center; }
        .btn-designar-stellar i { margin: 0 !important; }
    }
</style>
@endsection