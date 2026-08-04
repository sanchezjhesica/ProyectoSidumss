@extends('layouts.admin')

@section('content')
<div class="asignaciones-stellar">
    <!-- Cabecera de Página -->
    <div class="d-flex justify-content-between align-items-center mb-5 border-bottom pb-4">
        <div>
            <h2 class="text-purple mb-0"><i class="fas fa-key me-2"></i> Viviendas por Propietario</h2>
            <p class="text-muted mb-0">Consulte y gestione la relación entre residentes y sus propiedades.</p>
        </div>
        <a href="{{ route('admin.asignaciones.create') }}" class="btn btn-purple-stellar">
            <i class="fas fa-plus me-2"></i>Asignar Nueva
        </a>
    </div>

    @foreach($propietarios as $p)
    <div class="proprietor-block mb-4 shadow-sm">
        <!-- Encabezado del Propietario -->
        <div class="proprietor-header d-flex align-items-center">
            <div class="user-avatar-small me-3">
                <i class="fas fa-user"></i>
            </div>
            <div>
                <h5 class="mb-0 fw-bold text-dark">{{ $p->nombre }} {{ $p->apellido_paterno }}</h5>
                <small class="text-muted"><i class="fas fa-id-card me-1"></i> CI: {{ $p->ci }}</small>
            </div>
        </div>

        <!-- Lista de Viviendas -->
        <div class="viviendas-list p-3">
            @if($p->viviendas->count() > 0)
                <ul class="list-group list-group-flush">
                    @foreach($p->viviendas as $v)
                    <li class="list-group-item d-flex justify-content-between align-items-center border-0 py-3 rounded-3 mb-2 bg-light-hover">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-home text-purple me-3"></i>
                            <div>
                                <span class="fw-bold">Casa Nro: {{ $v->nro_casa }}</span>
                                <span class="text-muted ms-3">| Medidor: {{ $v->nro_medidor }}</span>
                            </div>
                        </div>
                        
                        <form action="{{ route('admin.asignaciones.destroy', [$p->id_usuario, $v->id_vivienda]) }}" method="POST">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger rounded-pill px-3 confirm-delete">
                                <i class="fas fa-trash-alt me-1"></i> Quitar
                            </button>
                        </form>
                    </li>
                    @endforeach
                </ul>
            @else
                <p class="text-center text-muted py-3 mb-0">Este propietario aún no tiene viviendas asignadas.</p>
            @endif
        </div>
    </div>
    @endforeach
</div>

<style>
    .text-purple { color: #5f4d93; }

    /* Estilo para los bloques de Propietario */
    .proprietor-block {
        border-radius: 15px;
        background: #fff;
        border: 1px solid #eee;
        overflow: hidden;
        transition: all 0.3s;
    }
    
    .proprietor-block:hover {
        border-color: #5f4d93;
        transform: translateX(5px);
    }

    .proprietor-header {
        background: #fcfcfc;
        padding: 15px 20px;
        border-bottom: 1px solid #f0f0f0;
    }

    .user-avatar-small {
        width: 40px;
        height: 40px;
        background: rgba(95, 77, 147, 0.1);
        color: #5f4d93;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.1rem;
    }

    /* Botón Stellar Principal */
    .btn-purple-stellar {
        background: #5f4d93;
        color: white;
        border-radius: 50px;
        padding: 10px 25px;
        font-weight: bold;
        border: none;
        transition: 0.3s;
    }
    
    .btn-purple-stellar:hover {
        background: #4a3b75;
        color: white;
        box-shadow: 0 5px 15px rgba(95, 77, 147, 0.3);
    }

    /* Efecto hover en items de la lista */
    .bg-light-hover:hover {
        background-color: #f8f9fa;
    }

    /* Ajuste para el botón de Quitar */
    .btn-outline-danger {
        border-width: 1px;
        font-size: 0.8rem;
    }
</style>
@endsection