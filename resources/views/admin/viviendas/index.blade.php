@extends('layouts.admin')

@section('content')
<div class="viviendas-stellar">
    <!-- 1. ENCABEZADO ESTILO STELLAR -->
    <div class="d-flex justify-content-between align-items-center mb-5 border-bottom pb-4">
        <div>
            <h2 class="text-purple mb-0"><i class="fas fa-city me-2"></i> Gestión de Viviendas</h2>
            <p class="text-muted mb-0">Administre el inventario de propiedades, medidores y tipos de vivienda de la urbanización.</p>
        </div>
        <a href="{{ route('admin.viviendas.create') }}" class="btn btn-purple-stellar shadow-sm">
            <i class="fas fa-plus-circle me-2"></i> Nueva Vivienda
        </a>
    </div>

    <!-- 2. MENSAJE DE ÉXITO ESTILIZADO -->
    @if(session('success'))
        <div class="alert alert-stellar alert-dismissible fade show mb-4" role="alert">
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
                            <th class="ps-4 py-3 uppercase-tracking">Casa</th>
                            <th class="py-3 uppercase-tracking">Medidor</th>
                            <th class="py-3 uppercase-tracking">Ubicación / Calle</th>
                            <th class="py-3 uppercase-tracking">Tipo</th>
                            <th class="py-3 text-center uppercase-tracking">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($viviendas as $v)
                        <tr>
                            <td class="ps-4">
                                <div class="d-flex align-items-center">
                                    <div class="property-icon me-3">
                                        <i class="fas fa-home"></i>
                                    </div>
                                    <span class="fw-bold text-dark fs-6">Casa #{{ $v->nro_casa }}</span>
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-purple-soft text-purple px-3 py-2 fw-bold">
                                    <i class="fas fa-tachometer-alt me-1"></i> {{ $v->nro_medidor }}
                                </span>
                            </td>
                            <td>
                                <span class="text-muted small"><i class="fas fa-map-marker-alt me-1"></i> {{ $v->calle ?? 'Sin dirección' }}</span>
                            </td>
                            <td>
                                @if($v->tipo_vivienda == 'Casa')
                                    <span class="text-dark small"><i class="fas fa-building me-1 text-muted"></i> Habitación</span>
                                @else
                                    <span class="text-muted small italic"><i class="fas fa-seedling me-1"></i> {{ $v->tipo_vivienda }}</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <div class="d-flex justify-content-center gap-2">
                                    {{-- BOTÓN EDITAR (Estilo Pill) --}}
                                    <a href="{{ route('admin.viviendas.edit', $v->id_vivienda) }}" class="btn btn-sm btn-edit-stellar">
                                        <i class="fas fa-edit me-1"></i> Editar
                                    </a>

                                    {{-- BOTÓN ELIMINAR (Estilo Icono Circular) --}}
                                    <form action="{{ route('admin.viviendas.destroy', $v->id_vivienda) }}" method="POST" onsubmit="return confirm('¿Eliminar esta vivienda?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-delete-stellar">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
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
    /* VARIABLES Y ESTILOS STELLAR */
    .text-purple { color: #5f4d93; }
    .bg-purple-soft { background-color: rgba(95, 77, 147, 0.08); }
    
    .uppercase-tracking {
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 1.5px;
        font-weight: 700;
        color: #888;
    }

    /* Icono de Propiedad */
    .property-icon {
        width: 35px;
        height: 35px;
        background: #f8f9fa;
        color: #5f4d93;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 1px solid #eee;
    }

    /* Botones de Acción */
    .btn-purple-stellar {
        background: linear-gradient(45deg, #5f4d93 0%, #e37682 100%);
        color: white;
        border-radius: 50px;
        padding: 10px 25px;
        font-weight: bold;
        border: none;
        transition: 0.3s;
    }
    .btn-purple-stellar:hover {
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(95, 77, 147, 0.3);
    }

    .btn-edit-stellar {
        color: #5f4d93;
        border: 1px solid #5f4d93;
        border-radius: 50px;
        padding: 5px 15px;
        font-weight: 600;
        transition: 0.3s;
    }
    .btn-edit-stellar:hover {
        background: #5f4d93;
        color: white;
    }

    .btn-delete-stellar {
        color: #e74c3c;
        border: 1px solid #e74c3c;
        border-radius: 50%;
        width: 32px;
        height: 32px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: 0.3s;
    }
    .btn-delete-stellar:hover {
        background: #e74c3c;
        color: white;
    }

    /* Alertas */
    .alert-stellar {
        background: #fff;
        border-left: 5px solid #5f4d93;
        color: #5f4d93;
        box-shadow: 0 4px 12px rgba(0,0,0,0.05);
        border-radius: 8px;
    }

    .rounded-4 { border-radius: 1rem !important; }
</style>
@endsection