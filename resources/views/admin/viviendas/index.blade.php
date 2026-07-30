@extends('layouts.admin')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Gestión de Viviendas</h2>
        <a href="{{ route('admin.viviendas.create') }}" class="btn btn-success">+ Nueva Vivienda</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card shadow">
        <div class="card-body p-0">
            <table class="table table-hover mb-0">
                <thead class="table-dark">
                    <tr>
                        <th class="ps-3">Nro Casa</th>
                        <th>Nro Medidor</th>
                        <th>Calle / Dirección</th>
                        <th>Tipo</th>
                        <th class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($viviendas as $v)
                    <tr>
                        <td class="ps-3">{{ $v->nro_casa }}</td>
                        <td><span class="badge bg-info text-dark">{{ $v->nro_medidor }}</span></td>
                        <td>{{ $v->calle ?? 'Sin dirección' }}</td>
                        <td>{{ $v->tipo_vivienda }}</td>
                        <td class="text-center">
                            <div class="d-flex justify-content-center gap-2">
                                <a href="{{ route('admin.viviendas.edit', $v->id_vivienda) }}" class="btn btn-sm btn-warning">Editar</a>
                                
                                <form action="{{ route('admin.viviendas.destroy', $v->id_vivienda) }}" method="POST" onsubmit="return confirm('¿Eliminar esta vivienda?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">Eliminar</button>
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