@extends('layouts.admin')
@section('content')
<div class="container">
    <h2 class="mb-4">Estado de Cuenta por Vivienda</h2>

    <div class="card shadow mb-4">
        <div class="card-body">
            <form action="{{ route('admin.reportes.vivienda') }}" method="GET" class="row g-3 align-items-end">
                <div class="col-md-8">
                    <label class="form-label">Seleccionar Vivienda / Casa</label>
                    <select name="id_vivienda" class="form-select" required>
                        <option value="">-- Seleccione una casa --</option>
                        @foreach($viviendas as $v)
                            <option value="{{ $v->id_vivienda }}" {{ request('id_vivienda') == $v->id_vivienda ? 'selected' : '' }}>
                                Casa {{ $v->nro_casa }} - Medidor: {{ $v->nro_medidor }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <button type="submit" class="btn btn-primary w-100">Generar Reporte Detallado</button>
                </div>
            </form>
        </div>
    </div>

    @if($viviendaSeleccionada)
    <div class="row">
        <div class="col-md-12 text-center mb-4">
            <h4>Detalle de la Casa: {{ $viviendaSeleccionada->nro_casa }}</h4>
            <p>Propietario: {{ $viviendaSeleccionada->propietarios->first()->nombre ?? 'Sin Asignar' }}</p>
        </div>

        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">Historial de Pagos</div>
                <div class="card-body p-0">
                    <table class="table table-sm mb-0">
                        <thead>
                            <tr><th>Periodo</th><th>Monto</th><th>Estado</th></tr>
                        </thead>
                        <tbody>
                            @foreach($historialPagos as $p)
                            <tr>
                                <td>{{ $p->periodo_mes }}/{{ $p->periodo_anio }}</td>
                                <td>Bs. {{ $p->total_pagar }}</td>
                                <td>
                                    <span class="badge {{ $p->estado_pago == 'Pagado' ? 'bg-success' : 'bg-danger' }}">
                                        {{ $p->estado_pago }}
                                    </span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-header bg-info text-white">Consumo de Agua</div>
                <div class="card-body p-0">
                    <table class="table table-sm mb-0">
                        <thead>
                            <tr><th>Fecha</th><th>L. Anterior</th><th>L. Actual</th><th>m³</th></tr>
                        </thead>
                        <tbody>
                            @foreach($historialLecturas as $l)
                            <tr>
                                <td>{{ \Carbon\Carbon::parse($l->fecha_lectura)->format('d/m/y') }}</td>
                                <td>{{ $l->lectura_anterior }}</td>
                                <td>{{ $l->lectura_actual }}</td>
                                <td class="fw-bold">{{ $l->lectura_actual - $l->lectura_anterior }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection