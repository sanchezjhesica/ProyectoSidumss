@extends('layouts.admin')

@section('content')
<div class="container">
    <h2>Editar Propietario</h2>
    <form action="{{ route('admin.usuarios.update', $usuario->id_usuario) }}" method="POST" class="card p-4 shadow-sm">
        @csrf
        @method('PUT') {{-- Esto es vital para que Laravel sepa que es una actualización --}}
        
        <div class="mb-3">
            <label class="form-label">Nombre</label>
            <input type="text" name="nombre" class="form-control" value="{{ $usuario->nombre }}" required>
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Apellido Paterno</label>
                <input type="text" name="apellido_paterno" class="form-control" value="{{ $usuario->apellido_paterno }}">
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Apellido Materno</label>
                <input type="text" name="apellido_materno" class="form-control" value="{{ $usuario->apellido_materno }}">
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label">CI</label>
            <input type="text" name="ci" class="form-control" value="{{ $usuario->ci }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control" value="{{ $usuario->email }}" required>
        </div>

        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-warning">Actualizar Datos</button>
            <a href="{{ route('admin.usuarios.index') }}" class="btn btn-secondary">Cancelar</a>
        </div>
    </form>
</div>
@endsection