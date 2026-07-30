@extends('layouts.propietario')
@section('content')
<div class="row">
    <div class="col-md-5">
        <div class="card shadow">
            <div class="card-header bg-primary text-white">Nueva Reserva</div>
            <div class="card-body">
                <form action="{{ route('propietario.reservas.store') }}" method="POST">
                    @csrf
                    <label>¿Qué desea reservar?</label>
                    <select name="id_area" class="form-select mb-3">
                        @foreach($areas as $area)
                            <option value="{{ $area->id_area }}">{{ $area->nombre_area }} (Bs. {{ $area->costo_estandar }})</option>
                        @endforeach
                    </select>
                    <label>Fecha</label>
                    <input type="date" name="fecha" class="form-control mb-3" required>
                    <button class="btn btn-success w-100">Confirmar Reserva</button>
                </form>
            </div>
        </div>
    </div>
    <div class="col-md-7">
        <h4>Mis Reservas Realizadas</h4>
        <table class="table small">
            <thead><tr><th>Fecha</th><th>Área</th><th>Costo</th><th>Estado</th></tr></thead>
            <tbody>
                @foreach($reservas as $r)
                <tr>
                    <td>{{ $r->fecha_reserva }}</td>
                    <td>Reserva</td> {{-- Aquí podrías hacer un join para el nombre --}}
                    <td>Bs. {{ $r->costo_pactado }}</td>
                    <td>{{ $r->estado_pago }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection