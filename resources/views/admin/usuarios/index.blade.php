@extends('layouts.admin')

@section('content')
<div class="usuarios-stellar">
    <!-- 1. ENCABEZADO RESPONSIVO -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4 mb-md-5 border-bottom pb-4 gap-3">
        <div>
            <h2 class="text-stellar-blue mb-0 fw-bold"><i class="fas fa-users-cog me-2"></i> Gestión de Usuarios</h2>
            <p class="text-muted mb-0">Administre los perfiles y permisos de acceso al sistema.</p>
        </div>
        <a href="{{ route('admin.usuarios.create') }}" class="btn btn-stellar shadow-sm w-100 w-md-auto">
            <i class="fas fa-plus-circle me-2"></i> Nuevo Usuario
        </a>
    </div>

    <!-- 2. MENSAJE DE ÉXITO -->
    @if(session('success'))
        <div class="alert alert-stellar alert-dismissible fade show mb-4" role="alert">
            <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- 3. TABLA CON CONTENEDOR RESPONSIVO -->
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4 py-3 uppercase-tracking">Identidad / CI</th>
                            <th class="py-3 uppercase-tracking">Nombre Completo</th>
                            <th class="py-3 uppercase-tracking d-none d-md-table-cell">Correo Electrónico</th>
                            <th class="py-3 text-center uppercase-tracking">Acciones de Gestión</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($usuarios as $u)
                        <tr>
                            <td class="ps-4">
                                <span class="badge bg-blue-soft text-stellar-blue px-3 py-2">
                                    {{ $u->ci }}
                                </span>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar-stellar me-2 me-md-3">
                                        {{ substr($u->nombre, 0, 1) }}{{ substr($u->apellido_paterno, 0, 1) }}
                                    </div>
                                    <div class="fw-bold text-dark name-container">
                                        <span class="d-block text-truncate" style="max-width: 150px;">{{ $u->nombre }} {{ $u->apellido_paterno }}</span>
                                        <div class="small text-muted fw-normal d-md-none">{{ $u->email }}</div>
                                        <div class="small text-muted fw-normal d-none d-md-block">{{ $u->apellido_materno }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="d-none d-md-table-cell">
                                <i class="far fa-envelope text-muted me-2"></i>{{ $u->email }}
                            </td>
                            <td class="pe-4">
                                <div class="d-flex justify-content-center gap-2">
                                    
                                <!-- NUEVO BOTÓN: RESTABLECER CONTRASEÑA DIRECTO -->
                                    <form action="{{ route('admin.usuarios.reset', $u->id_usuario) }}" method="POST" onsubmit="return confirm('¿Restablecer contraseña a sidumss123?')">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-reset-stellar" title="Restablecer a sidumss123">
                                            <i class="fas fa-key"></i> <span class="d-none d-lg-inline ms-1">Clave</span>
                                        </button>
                                    </form>

                                    <!-- EDITAR -->
                                    <a href="{{ route('admin.usuarios.edit', $u->id_usuario) }}" class="btn btn-sm btn-edit-stellar" title="Editar Perfil">
                                        <i class="fas fa-edit"></i> <span class="d-none d-lg-inline ms-1">Editar</span>
                                    </a>

                                    <!-- ELIMINAR -->
                                    <form action="{{ route('admin.usuarios.destroy', $u->id_usuario) }}" method="POST" onsubmit="return confirm('¿Eliminar usuario?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-delete-stellar" title="Eliminar">
                                            <i class="fas fa-trash"></i> <span class="d-none d-lg-inline ms-1">Borrar</span>
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
    :root {
        --stellar-blue: #0e5cad;
        --stellar-grad: linear-gradient(45deg, #79f1a4 15%, #0e5cad 85%);
        --stellar-button: linear-gradient(45deg, #22349e 0%, #8183e6 100%);
    }

    .text-stellar-blue { color: var(--stellar-blue); }
    .bg-blue-soft { background-color: rgba(14, 92, 173, 0.08); }
    
    .uppercase-tracking {
        font-size: 0.65rem;
        text-transform: uppercase;
        letter-spacing: 1.2px;
        font-weight: 700;
        color: #888;
    }

    .avatar-stellar {
        width: 38px; height: 35px;
        background: var(--stellar-grad);
        color: white; border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        font-weight: bold; font-size: 0.75rem; flex-shrink: 0;
    }

    /* ESTILO DE BOTONES CON TEXTO (PILL STYLE) */
    .btn-edit-stellar, .btn-delete-stellar, .btn-reset-stellar {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 50px;
        padding: 5px 12px;
        font-weight: 700;
        font-size: 0.75rem;
        text-transform: uppercase;
        transition: all 0.3s ease;
        white-space: nowrap;
    }

    /* Restablecer (Ámbar) */
    .btn-reset-stellar {
        color: #f39c12;
        border: 1px solid #f39c12;
        background-color: #fffaf0;
    }
    .btn-reset-stellar:hover {
        background-color: #f39c12;
        color: white;
    }

    /* Editar (Azul) */
    .btn-edit-stellar {
        color: var(--stellar-blue);
        border: 1px solid var(--stellar-blue);
        background-color: #f0f7ff;
    }
    .btn-edit-stellar:hover {
        background-color: var(--stellar-blue);
        color: white;
    }

    /* Borrar (Rojo) */
    .btn-delete-stellar {
        color: #e74c3c;
        border: 1px solid #e74c3c;
        background-color: #fff5f5;
    }
    .btn-delete-stellar:hover {
        background-color: #e74c3c;
        color: white;
    }

    /* Botón Crear Usuario */
    .btn-stellar {
        background: var(--stellar-button);
        color: white !important;
        border-radius: 50px;
        padding: 10px 25px;
        font-weight: 700;
        border: none;
        transition: 0.3s;
        text-transform: uppercase;
        font-size: 0.8rem;
    }

    @media (max-width: 992px) {
        /* En pantallas medianas ocultamos las letras para ahorrar espacio */
        .btn-edit-stellar span, .btn-delete-stellar span, .btn-reset-stellar span {
            display: none;
        }
        .btn-edit-stellar, .btn-delete-stellar, .btn-reset-stellar {
            width: 32px; height: 32px; padding: 0; border-radius: 50%;
        }
    }
</style>
@endsection