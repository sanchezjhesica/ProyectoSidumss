@extends('layouts.admin')
@section('content')
<div class="container">
    <h2>Asignar Vivienda a Propietario</h2>
    <div class="card shadow">
        <div class="card-body">
            <form action="{{ route('admin.asignaciones.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label>Seleccionar Propietario</label>
                    <select name="id_usuario" class="form-select" required>
                        <option value="">-- Seleccione un usuario --</option>
                        @foreach($usuarios as $u)
                            <option value="{{ $u->id_usuario }}">{{ $u->nombre }} {{ $u->apellido_paterno }} (CI: {{ $u->ci }})</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label>Seleccionar Vivienda</label>
                    <select name="id_vivienda" class="form-select" required>
                        <option value="">-- Seleccione una vivienda --</option>
                        @foreach($viviendas as $v)
                            <option value="{{ $v->id_vivienda }}">Casa: {{ $v->nro_casa }} | Medidor: {{ $v->nro_medidor }}</option>
                        @endforeach
                    </select>
                </div>

                <button type="submit" class="btn btn-success">Confirmar Asignación</button>
                <a href="{{ route('admin.asignaciones.index') }}" class="btn btn-secondary">Cancelar</a>
            </form>
        </div>
    </div>
</div>
@endsection