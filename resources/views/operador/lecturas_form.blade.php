@extends('layouts.operador')

@section('content')
<div class="container">
    <h2>Panel de Trabajo - Operador</h2>

    <div class="card shadow border-primary mt-4">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Registrar Consumo de Agua</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('operador.lecturas.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="fw-bold">Seleccionar Vivienda</label>
                    <select name="id_vivienda" class="form-select" required>
                        @foreach($viviendas as $v)
                            <option value="{{ $v->id_vivienda }}">Casa {{ $v->nro_casa }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label class="fw-bold">Lectura Actual (m³)</label>
                    <input type="number" step="0.01" name="lectura_actual" class="form-control" required>
                </div>

                <button type="submit" class="btn btn-primary w-100">Guardar Lectura</button>
            </form>
        </div> {{-- CIERRE DE CARD-BODY --}}
    </div> {{-- CIERRE DE CARD --}}
</div> {{-- CIERRE DE CONTAINER --}}
@endsection