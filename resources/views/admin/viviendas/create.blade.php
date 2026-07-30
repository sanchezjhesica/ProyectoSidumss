@extends('layouts.admin')

@section('content')
<div class="container">
    <h2>Registrar Nueva Vivienda</h2>
    <div class="card shadow">
        <div class="card-body">
            <form action="{{ route('admin.viviendas.store') }}" method="POST">
                @csrf
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Número de Casa</label>
                        <input type="text" name="nro_casa" class="form-control" placeholder="Ej: 10-A" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Número de Medidor</label>
                        <input type="text" name="nro_medidor" class="form-control" placeholder="Ej: MED-001" required>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Calle / Dirección</label>
                    <input type="text" name="calle" class="form-control" placeholder="Nombre de la calle">
                </div>
                <div class="mb-3">
                    <label class="form-label">Tipo de Vivienda</label>
                    <select name="tipo_vivienda" class="form-select">
                        <option value="Casa">Casa</option>
                        <option value="Lote Vacio">Lote Vacio</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-success">Guardar Vivienda</button>
                <a href="{{ route('admin.viviendas.index') }}" class="btn btn-secondary">Volver</a>
            </form>
        </div>
    </div>
</div>
@endsection