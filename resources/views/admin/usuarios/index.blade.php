@extends('layouts.admin')

@section('content')
<div class="usuarios-stellar">
    <!-- 1. ENCABEZADO ESTILO STELLAR -->
    <div class="d-flex justify-content-between align-items-center mb-5 border-bottom pb-4">
        <div>
            <h2 class="text-purple mb-0"><i class="fas fa-users-cog me-2"></i> Gestión de Usuarios</h2>
            <p class="text-muted mb-0">Administre los perfiles, accesos y datos de contacto de los residentes.</p>
        </div>
        <a href="{{ route('admin.usuarios.create') }}" class="btn btn-purple-stellar shadow-sm">
            <i class="fas fa-plus-circle me-2"></i> Nuevo Usuario
        </a>
    </div>

    <!-- 2. MENSAJE DE ÉXITO ESTILIZADO -->
    @if(session('success'))
        <div class="alert alert-stellar alert-dismissible fade show mb-4" role="alert">
            <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- 3. TABLA LIMPIA Y MODERNA -->
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4 py-3 uppercase-tracking">Identidad / CI</th>
                            <th class="py-3 uppercase-tracking">Nombre Completo</th>
                            <th class="py-3 uppercase-tracking">Correo Electrónico</th>
                            <th class="py-3 text-center uppercase-tracking">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($usuarios as $u)
                        <tr>
                            <td class="ps-4">
                                <span class="badge bg-purple-soft text-purple px-3 py-2">
                                    {{ $u->ci }}
                                </span>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <!-- Avatar con iniciales y degradado -->
                                    <div class="avatar-stellar me-3">
                                        {{ substr($u->nombre, 0, 1) }}{{ substr($u->apellido_paterno, 0, 1) }}
                                    </div>
                                    <div class="fw-bold text-dark">
                                        {{ $u->nombre }} {{ $u->apellido_paterno }}
                                        <div class="small text-muted fw-normal">{{ $u->apellido_materno }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <i class="far fa-envelope text-muted me-2"></i>{{ $u->email }}
                            </td>
                            <td class="text-center">
                                <div class="d-flex justify-content-center gap-2">
                                    {{-- BOTÓN EDITAR (Pill Style) --}}
                                    <a href="{{ route('admin.usuarios.edit', $u->id_usuario) }}" class="btn btn-sm btn-edit-stellar">
                                        <i class="fas fa-edit me-1"></i> Editar
                                    </a>

                                    {{-- BOTÓN ELIMINAR (Icon Style) --}}
                                    <form action="{{ route('admin.usuarios.destroy', $u->id_usuario) }}" method="POST" onsubmit="return confirm('¿Estás seguro de eliminar a este usuario?')">
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
    /* VARIABLES STELLAR */
    .text-purple { color: #5f4d93; }
    .bg-purple-soft { background-color: rgba(95, 77, 147, 0.08); }
    
    .uppercase-tracking {
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 1.5px;
        font-weight: 700;
        color: #888;
    }

    /* AVATAR CON DEGRADADO */
    .avatar-stellar {
        width: 40px;
        height: 40px;
        background: linear-gradient(45deg, #5f4d93, #e37682);
        color: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        font-size: 0.85rem;
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }

    /* BOTONES */
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
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(95, 77, 147, 0.3);
    }

    .btn-edit-stellar {
        color: #5f4d93;
        border: 1px solid #5f4d93;
        border-radius: 50px;
        padding: 5px 15px;
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

    /* ALERTAS */
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