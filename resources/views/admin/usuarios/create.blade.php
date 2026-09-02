@extends('layouts.admin')

@section('content')
<div class="usuarios-create-stellar">
    <!-- 1. CABECERA (Solo título) -->
    <div class="mb-4 mb-md-5 border-bottom pb-4">
        <h2 class="text-stellar-blue mb-0 fw-bold"><i class="fas fa-user-plus me-2"></i> Registrar Propietario</h2>
        <p class="text-muted mb-0">Cree una nueva cuenta de acceso para un residente de la urbanización.</p>
    </div>

    <!-- 2. ALERTA DE ERRORES -->
    @if ($errors->any())
        <div class="alert alert-stellar-danger mb-4 shadow-sm animate__animated animate__shakeX">
            <div class="fw-bold mb-2"><i class="fas fa-exclamation-triangle me-2"></i> Atención:</div>
            <ul class="mb-0 small">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- 3. TARJETA DEL FORMULARIO -->
    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-4 p-md-5">
            <form action="{{ route('admin.usuarios.store') }}" method="POST">
                @csrf 
                
                <!-- Sección: Información Personal -->
                <div class="form-section mb-5">
                    <h5 class="text-stellar-blue fw-bold mb-4 border-bottom pb-2">
                        <i class="fas fa-id-card me-2"></i>Información Personal
                    </h5>
                    <div class="row g-3 g-md-4">
                        <div class="col-md-4">
                            <label class="form-label-stellar">Nombre(s)</label>
                            <input type="text" name="nombre" class="form-control form-stellar" value="{{ old('nombre') }}" placeholder="Ej. Juan" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label-stellar">Apellido Paterno</label>
                            <input type="text" name="apellido_paterno" class="form-control form-stellar" value="{{ old('apellido_paterno') }}" placeholder="Ej. Pérez">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label-stellar">Apellido Materno</label>
                            <input type="text" name="apellido_materno" class="form-control form-stellar" value="{{ old('apellido_materno') }}" placeholder="Ej. Mamani">
                        </div>
                    </div>
                </div>

                <!-- Sección: Documentación -->
                <div class="form-section mb-5">
                    <h5 class="text-stellar-blue fw-bold mb-4 border-bottom pb-2">
                        <i class="fas fa-address-book me-2"></i>Documentación y Contacto
                    </h5>
                    <div class="row g-3 g-md-4">
                        <div class="col-md-6">
                            <label class="form-label-stellar">Cédula de Identidad (CI)</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="fas fa-fingerprint text-muted"></i></span>
                                <input type="text" name="ci" class="form-control form-stellar border-start-0" value="{{ old('ci') }}" placeholder="Número de documento" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label-stellar">Teléfono / Celular</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="fas fa-mobile-alt text-muted"></i></span>
                                <input type="text" name="telefono" class="form-control form-stellar border-start-0" value="{{ old('telefono') }}" placeholder="Ej. 70000000">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sección: Acceso -->
                <!-- Sección: Acceso -->
                <div class="form-section mb-5">
                    <h5 class="text-stellar-blue fw-bold mb-4 border-bottom pb-2">
                        <i class="fas fa-key me-2"></i>Credenciales de Acceso
                    </h5>
                    <div class="row g-3 g-md-4">
                        <div class="col-md-4">
                            <label class="form-label-stellar">Correo Electrónico</label>
                            <input type="email" name="email" 
                                class="form-control form-stellar @error('email') is-invalid @enderror" 
                                value="{{ old('email') }}" placeholder="usuario@correo.com" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label-stellar">Contraseña Inicial</label>
                            <input type="password" name="password" 
                                class="form-control form-stellar @error('password') is-invalid @enderror" 
                                placeholder="Mínimo 8 caracteres" required>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <!-- Nuevo campo para confirmación -->
                        <div class="col-md-4">
                            <label class="form-label-stellar">Confirmar Contraseña</label>
                            <input type="password" name="password_confirmation" class="form-control form-stellar" 
                                placeholder="Repita la contraseña" required>
                        </div>
                    </div>
                </div>
                <!-- 4. BOTONES DE ACCIÓN (ABAJO) -->
                <div class="pt-4 border-top d-flex flex-column flex-md-row gap-3 justify-content-md-end">
                    <a href="{{ route('admin.usuarios.index') }}" class="btn btn-outline-secondary rounded-pill px-4 d-flex align-items-center justify-content-center order-2 order-md-1">
                        <i class="fas fa-arrow-left me-2"></i> Cancelar y Volver
                    </a>
                    <button type="submit" class="btn btn-stellar-blue btn-lg px-5 shadow-sm order-1 order-md-2">
                        <i class="fas fa-save me-2"></i> Guardar Nuevo Propietario
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    /* VARIABLES UNIFICADAS STELLAR */
    :root {
        --stellar-blue: #0e5cad;
        --stellar-button: linear-gradient(45deg, #22349e 0%, #8183e6 100%);
        --stellar-input-focus: rgba(14, 92, 173, 0.1);
    }

    .text-stellar-blue { color: var(--stellar-blue); }

    .form-label-stellar {
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 1px;
        font-weight: 700;
        color: #777;
        margin-bottom: 8px;
        display: block;
    }

    .form-stellar {
        border: 1px solid #e0e0e0;
        border-radius: 10px;
        padding: 12px 15px;
        background-color: #fdfdfd;
        transition: all 0.3s ease;
        font-size: 0.95rem;
    }
    .form-stellar:focus {
        border-color: var(--stellar-blue);
        background-color: #fff;
        box-shadow: 0 0 0 0.25rem var(--stellar-input-focus);
    }

    .input-group-text {
        border-radius: 10px 0 0 10px !important;
        border: 1px solid #e0e0e0;
        color: #aaa;
    }

    /* Botón Stellar Blue */
    .btn-stellar-blue {
        background: var(--stellar-button);
        color: white !important;
        border-radius: 50px;
        font-weight: 700;
        border: none;
        padding: 14px 35px;
        transition: 0.3s;
        text-transform: uppercase;
        letter-spacing: 1px;
        font-size: 0.85rem;
    }
    .btn-stellar-blue:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(34, 52, 158, 0.3);
    }

    .alert-stellar-danger {
        background-color: #fff5f5;
        border-left: 5px solid #e74c3c;
        color: #c0392b;
        padding: 20px;
        border-radius: 12px;
    }

    .rounded-4 { border-radius: 1.25rem !important; }

    /* Ajustes para móviles */
    @media (max-width: 767.98px) {
        .card-body { padding: 1.5rem !important; }
        .btn-stellar-blue, .btn-outline-secondary { width: 100%; }
        h2 { font-size: 1.5rem; }
    }
</style>
@endsection