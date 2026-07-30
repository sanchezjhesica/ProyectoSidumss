@extends('layouts.admin')

@section('content')
<div class="container">
    <h2>Editar Vivienda: {{ $vivienda->nro_casa }}</h2>
    <div class="card shadow">
        <div class="card-body">
            <form action="{{ route('admin.viviendas.update', $vivienda->id_vivienda) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Número de Casa</label>
                        <input type="text" name="nro_casa" class="form-control" value="{{ $vivienda->nro_casa }}" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Número de Medidor</label>
                        <input type="text" name="nro_medidor" class="form-control" value="{{ $vivienda->nro_medidor }}" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Calle / Dirección</label>
                    <input type="text" name="calle" class="form-control" value="{{ $vivienda->calle }}">
                </div>

                <div class="mb-3">
                    <label class="form-label">Tipo de Vivienda</label>
                    <select name="tipo_vivienda" class="form-select">
                        <option value="Casa" {{ $vivienda->tipo_vivienda == 'Casa' ? 'selected' : '' }}>Casa</option>
                        <option value="Lote Vacio" {{ $vivienda->tipo_vivienda == 'Lote Vacio' ? 'selected' : '' }}>Lote Vacio</option>
                    </select>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">Actualizar Vivienda</button>
                    <a href="{{ route('admin.viviendas.index') }}" class="btn btn-secondary">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection