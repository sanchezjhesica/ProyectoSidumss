@extends('layouts.admin')
@section('content')
<div class="container">
    <div class="d-flex justify-content-between mb-4">
        <h2>Viviendas por Propietario</h2>
        <a href="{{ route('admin.asignaciones.create') }}" class="btn btn-primary">Asignar Nueva</a>
    </div>

    @foreach($propietarios as $p)
    <div class="card mb-3 shadow-sm">
        <div class="card-header bg-light">
            <strong>Propietario:</strong> {{ $p->nombre }} {{ $p->apellido_paterno }} (CI: {{ $p->ci }})
        </div>
        <div class="card-body">
            <ul>
                @foreach($p->viviendas as $v)
                <li class="d-flex justify-content-between mb-2">
                    Casa Nro: {{ $v->nro_casa }} - Medidor: {{ $v->nro_medidor }}
                    <form action="{{ route('admin.asignaciones.destroy', [$p->id_usuario, $v->id_vivienda]) }}" method="POST">
                        @csrf @method('DELETE')
                        <button class="btn btn-sm btn-outline-danger">Quitar</button>
                    </form>
                </li>
                @endforeach
            </ul>
        </div>
    </div>
    @endforeach
</div>
@endsection