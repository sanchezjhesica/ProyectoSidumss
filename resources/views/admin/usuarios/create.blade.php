@extends('layouts.admin')

@section('content')
<div class="usuarios-create-stellar">
    <!-- Cabecera del Formulario -->
    <div class="d-flex justify-content-between align-items-center mb-5 border-bottom pb-4">
        <div>
            <h2 class="text-purple mb-0"><i class="fas fa-user-plus me-2"></i> Registrar Nuevo Propietario</h2>
            <p class="text-muted mb-0">Complete la información para crear una nueva cuenta de acceso al sistema.</p>
        </div>
        <a href="{{ route('admin.usuarios.index') }}" class="btn btn-outline-secondary rounded-pill px-4">
            <i class="fas fa-arrow-left me-2"></i> Volver
        </a>
    </div>

    <!-- Alerta de Errores Estilizada -->
    @if ($errors->any())
        <div class="alert alert-stellar-danger mb-4 shadow-sm">
            <div class="fw-bold mb-2"><i class="fas fa-exclamation-circle me-2"></i> Por favor corrija los siguientes errores:</div>
            <ul class="mb-0 small">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Tarjeta del Formulario -->
    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-5">
            <form action="{{ route('admin.usuarios.store') }}" method="POST">
                @csrf 
                
                <h5 class="text-purple fw-bold mb-4 border-bottom pb-2">Información Personal</h5>
                <div class="row g-4 mb-5">
                    <div class="col-md-4">
                        <label class="form-label fw-bold small text-uppercase tracking-wider"><i class="fas fa-user me-1 text-muted"></i> Nombre</label>
                        <input type="text" name="nombre" class="form-control form-stellar" value="{{ old('nombre') }}" placeholder="Ej. Juan" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-bold small text-uppercase tracking-wider">Apellido Paterno</label>
                        <input type="text" name="apellido_paterno" class="form-control form-stellar" value="{{ old('apellido_paterno') }}" placeholder="Ej. Pérez">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-bold small text-uppercase tracking-wider">Apellido Materno</label>
                        <input type="text" name="apellido_materno" class="form-control form-stellar" value="{{ old('apellido_materno') }}" placeholder="Ej. Mamani">
                    </div>
                </div>

                <h5 class="text-purple fw-bold mb-4 border-bottom pb-2">Documentación y Contacto</h5>
                <div class="row g-4 mb-5">
                    <div class="col-md-6">
                        <label class="form-label fw-bold small text-uppercase tracking-wider"><i class="fas fa-id-card me-1 text-muted"></i> CI (Cédula de Identidad)</label>
                        <input type="text" name="ci" class="form-control form-stellar" value="{{ old('ci') }}" placeholder="Número de documento" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold small text-uppercase tracking-wider"><i class="fas fa-phone me-1 text-muted"></i> Teléfono / Celular</label>
                        <input type="text" name="telefono" class="form-control form-stellar" value="{{ old('telefono') }}" placeholder="Ej. 70000000">
                    </div>
                </div>

                <h5 class="text-purple fw-bold mb-4 border-bottom pb-2">Credenciales de Acceso</h5>
                <div class="row g-4 mb-5">
                    <div class="col-md-6">
                        <label class="form-label fw-bold small text-uppercase tracking-wider"><i class="fas fa-envelope me-1 text-muted"></i> Correo Electrónico</label>
                        <input type="email" name="email" class="form-control form-stellar" value="{{ old('email') }}" placeholder="usuario@correo.com" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold small text-uppercase tracking-wider"><i class="fas fa-key me-1 text-muted"></i> Contraseña de acceso</label>
                        <input type="password" name="password" class="form-control form-stellar" placeholder="••••••••" required>
                        <div class="form-text mt-2"><i class="fas fa-info-circle me-1"></i> La contraseña debe tener al menos 6 caracteres.</div>
                    </div>
                </div>

                <div class="pt-4 border-top">
                    <button type="submit" class="btn btn-purple-stellar btn-lg px-5 shadow-sm">
                        <i class="fas fa-save me-2"></i> Guardar Propietario
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    .text-purple { color: #5f4d93; }
    .tracking-wider { letter-spacing: 1px; color: #777; }

    /* Estilo de los inputs */
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

    /* Botón Stellar Principal */
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

    /* Alerta Personalizada */
    .alert-stellar-danger {
        background-color: #fff5f5;
        border-left: 5px solid #e74c3c;
        color: #c0392b;
        padding: 20px;
        border-radius: 10px;
    }

    .rounded-4 { border-radius: 1.25rem !important; }
</style>
@endsection