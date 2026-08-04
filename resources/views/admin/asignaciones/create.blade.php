@extends('layouts.admin')

@section('content')
<div class="form-container-stellar">
    <h2 class="mb-4"><i class="fas fa-link text-purple"></i> Asignar Vivienda a Propietario</h2>
    <p class="text-muted mb-5">Vincule a un propietario con su respectiva vivienda y medidor dentro del sistema.</p>

    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-5">
            <form action="{{ route('admin.asignaciones.store') }}" method="POST">
                @csrf
                
                <div class="mb-4">
                    <label class="form-label fw-bold text-dark">
                        <i class="fas fa-user-tie me-2"></i>Seleccionar Propietario
                    </label>
                    <select name="id_usuario" class="form-select form-stellar" required>
                        <option value="" disabled selected>-- Elija un usuario registrado --</option>
                        @foreach($usuarios as $u)
                            <option value="{{ $u->id_usuario }}">
                                {{ $u->nombre }} {{ $u->apellido_paterno }} (CI: {{ $u->ci }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-5">
                    <label class="form-label fw-bold text-dark">
                        <i class="fas fa-home me-2"></i>Seleccionar Vivienda
                    </label>
                    <select name="id_vivienda" class="form-select form-stellar" required>
                        <option value="" disabled selected>-- Elija una vivienda --</option>
                        @foreach($viviendas as $v)
                            <option value="{{ $v->id_vivienda }}">
                                Casa Nro: {{ $v->nro_casa }} | Medidor: {{ $v->nro_medidor }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="d-flex gap-3">
                    <button type="submit" class="btn btn-purple-stellar px-5">
                        <i class="fas fa-save me-2"></i>Confirmar Asignación
                    </button>
                    <a href="{{ route('admin.asignaciones.index') }}" class="btn btn-outline-secondary rounded-pill px-4">
                        Cancelar
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    /* Aseguramos consistencia con el estilo Stellar */
    .text-purple { color: #5f4d93; }
    
    .form-stellar {
        border: 1px solid #e0e0e0;
        border-radius: 10px;
        padding: 12px 15px;
        background-color: #fcfcfc;
        transition: all 0.3s;
    }

    .form-stellar:focus {
        border-color: #5f4d93;
        box-shadow: 0 0 0 0.25rem rgba(95, 77, 147, 0.1);
        background-color: #fff;
    }

    .btn-purple-stellar {
        background: #5f4d93;
        color: white;
        border-radius: 50px;
        font-weight: bold;
        padding: 12px 25px;
        border: none;
        transition: 0.3s;
    }

    .btn-purple-stellar:hover {
        background: #4a3b75;
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(95, 77, 147, 0.3);
    }

    .rounded-4 { border-radius: 1.2rem !important; }
</style>
@endsection