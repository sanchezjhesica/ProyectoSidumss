@extends('layouts.admin')

@section('content')
<div class="container">
    {{-- 1. ENCABEZADO (Fuera de la tabla) --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Gestión de Usuarios</h2>
        <a href="{{ route('admin.usuarios.create') }}" class="btn btn-primary">
            + Nuevo Usuario
        </a>
    </div>

    {{-- 2. MENSAJE DE ÉXITO --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- 3. TABLA --}}
    <div class="card shadow">
        <div class="card-body p-0"> {{-- p-0 para que la tabla ocupe todo el espacio --}}
            <table class="table table-hover mb-0">
                <thead class="table-dark">
                    <tr>
                        <th class="ps-3">CI</th>
                        <th>Nombre Completo</th>
                        <th>Email</th>
                        <th class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($usuarios as $u)
                    <tr>
                        <td class="ps-3">{{ $u->ci }}</td>
                        <td>{{ $u->nombre }} {{ $u->apellido_paterno }} {{ $u->apellido_materno }}</td>
                        <td>{{ $u->email }}</td>
                        <td class="text-center">
                            <div class="d-flex justify-content-center gap-2">
                                {{-- BOTÓN EDITAR --}}
                                <a href="{{ route('admin.usuarios.edit', $u->id_usuario) }}" class="btn btn-sm btn-warning">
                                    Editar
                                </a>

                                {{-- BOTÓN ELIMINAR --}}
                                <form action="{{ route('admin.usuarios.destroy', $u->id_usuario) }}" method="POST" onsubmit="return confirm('¿Estás seguro de eliminar a este usuario?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">
                                        Eliminar
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
@endsection