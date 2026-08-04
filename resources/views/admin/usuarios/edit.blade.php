@extends('layouts.admin')

@section('content')
<div class="usuarios-edit-stellar">
    <!-- Cabecera de Edición -->
    <div class="d-flex justify-content-between align-items-center mb-5 border-bottom pb-4">
        <div>
            <h2 class="text-purple mb-0"><i class="fas fa-user-edit me-2"></i> Editar Propietario</h2>
            <p class="text-muted mb-0">Modifique la información del perfil del usuario <strong>{{ $usuario->nombre }}</strong>.</p>
        </div>
        <a href="{{ route('admin.usuarios.index') }}" class="btn btn-outline-secondary rounded-pill px-4">
            <i class="fas fa-times me-2"></i> Cancelar
        </a>
    </div>

    <!-- Tarjeta del Formulario -->
    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-5">
            <form action="{{ route('admin.usuarios.update', $usuario->id_usuario) }}" method="POST">
                @csrf
                @method('PUT')

                <h5 class="text-purple fw-bold mb-4 border-bottom pb-2">Información de Identidad</h5>
                
                <div class="mb-4">
                    <label class="form-label fw-bold small text-uppercase tracking-wider">Nombre Completo</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0"><i class="fas fa-user text-muted"></i></span>
                        <input type="text" name="nombre" class="form-control form-stellar border-start-0" value="{{ $usuario->nombre }}" required>
                    </div>
                </div>

                <div class="row g-4 mb-5">
                    <div class="col-md-6">
                        <label class="form-label fw-bold small text-uppercase tracking-wider">Apellido Paterno</label>
                        <input type="text" name="apellido_paterno" class="form-control form-stellar" value="{{ $usuario->apellido_paterno }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold small text-uppercase tracking-wider">Apellido Materno</label>
                        <input type="text" name="apellido_materno" class="form-control form-stellar" value="{{ $usuario->apellido_materno }}">
                    </div>
                </div>

                <h5 class="text-purple fw-bold mb-4 border-bottom pb-2">Documentación y Contacto</h5>
                <div class="row g-4 mb-5">
                    <div class="col-md-6">
                        <label class="form-label fw-bold small text-uppercase tracking-wider"><i class="fas fa-id-card me-1 text-muted"></i> CI (Cédula de Identidad)</label>
                        <input type="text" name="ci" class="form-control form-stellar" value="{{ $usuario->ci }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold small text-uppercase tracking-wider"><i class="fas fa-envelope me-1 text-muted"></i> Correo Electrónico</label>
                        <input type="email" name="email" class="form-control form-stellar" value="{{ $usuario->email }}" required>
                    </div>
                </div>

                <div class="pt-4 border-top d-flex gap-3">
                    <button type="submit" class="btn btn-purple-stellar px-5 shadow-sm">
                        <i class="fas fa-sync-alt me-2"></i> Actualizar Datos
                    </button>
                    <a href="{{ route('admin.usuarios.index') }}" class="btn btn-light rounded-pill px-4">
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

    /* Estilo de los inputs mejorado */
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
    
    .input-group-text {
        border-radius: 10px 0 0 10px !important;
        border: 1px solid #e0e0e0;
    }

    /* Botón Stellar Principal */
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