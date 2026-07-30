@extends('layouts.operador')
@section('content')
<div class="container">
    <h2 class="mb-4">Panel de Trabajo - Operador</h2>

    <div class="row">
        <!-- FORMULARIO DE LECTURA -->
        <div class="col-md-6 mb-4">
            <div class="card shadow border-primary">
                <div class="card-header bg-primary text-white">Registrar Consumo de Agua</div>
                <div class="card-body">
                    <form action="{{ route('operador.lecturas.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label>Seleccionar Casa</label>
                            <select name="id_vivienda" class="form-select" required>
                                @foreach($viviendas as $v)
                                    <option value="{{ $v->id_vivienda }}">Casa {{ $v->nro_casa }} (Medidor: {{ $v->nro_medidor }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label>Lectura Actual del Medidor</label>
                            <input type="number" name="lectura_actual" class="form-control form-control-lg" placeholder="0000.00" required>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Guardar Lectura</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- REPORTE DE MEDIDOR MAL -->
        <div class="col-md-6">
            <div class="card shadow border-danger">
                <div class="card-header bg-danger text-white">Reportar Medidor con Problemas</div>
                <div class="card-body">
                    <form action="{{ route('operador.averias.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label>Casa con el Problema</label>
                            <select name="id_vivienda" class="form-select" required>
                                @foreach($viviendas as $v)
                                    <option value="{{ $v->id_vivienda }}">Casa {{ $v->nro_casa }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label>Descripción del Daño</label>
                            <textarea name="descripcion" class="form-control" rows="3" placeholder="Ej: El medidor no gira, el vidrio está empañado..." required></textarea>
                        </div>
                        <button type="submit" class="btn btn-outline-danger w-100">Enviar Reporte Técnico</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection