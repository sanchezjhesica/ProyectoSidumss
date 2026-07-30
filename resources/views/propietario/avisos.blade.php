@extends('layouts.propietario')
@section('content')
<h2>Mis Avisos de Cobro</h2>
<table class="table table-hover mt-3">
    <thead>
        <tr>
            <th>Periodo</th>
            <th>Monto Total</th>
            <th>Estado</th>
            <th>Acción</th>
        </tr>
    </thead>
    <tbody>
        @foreach($avisos as $a)
        <tr>
            <td>{{ $a->periodo_mes }}/{{ $a->periodo_anio }}</td>
            <td>Bs. {{ $a->total_pagar }}</td>
            <td>
                <span class="badge {{ $a->estado_pago == 'Pagado' ? 'bg-success' : 'bg-danger' }}">
                    {{ $a->estado_pago }}
                </span>
            </td>
            <td>
                <a href="{{ route('admin.lecturas.recibo', $a->id_vivienda) }}" class="btn btn-sm btn-outline-primary">Ver Detalle</a>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@endsection