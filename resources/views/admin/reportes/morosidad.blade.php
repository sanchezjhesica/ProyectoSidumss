@extends('layouts.admin')
@section('content')
<div class="container">
    <h2 class="mb-4 text-danger">Reporte de Morosidad</h2>
    
    <div class="card shadow">
        <div class="card-header bg-danger text-white">
            Lista de Cobros Pendientes (Deudores)
        </div>
        <div class="card-body p-0">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Casa</th>
                        <th>Propietario</th>
                        <th>Periodo</th>
                        <th>Monto Pendiente</th>
                        <th>Estado</th>
                    </tr>
                </thead>
                <tbody>
                    @php $totalDeuda = 0; @endphp
                    @forelse($morosos as $m)
                    <tr>
                        <td>{{ $m->vivienda->nro_casa }}</td>
                        <td>{{ $m->vivienda->propietarios->first()->nombre ?? 'S/N' }}</td>
                        <td>{{ $m->periodo_mes }}/{{ $m->periodo_anio }}</td>
                        <td class="text-danger fw-bold">Bs. {{ number_format($m->total_pagar, 2) }}</td>
                        <td><span class="badge bg-warning text-dark">{{ $m->estado_pago }}</span></td>
                    </tr>
                    @php $totalDeuda += $m->total_pagar; @endphp
                    @empty
                    <tr>
                        <td colspan="5" class="text-center">No hay morosos registrados. ¡Todos están al día!</td>
                    </tr>
                    @endforelse
                </tbody>
                @if($morosos->count() > 0)
                <tfoot class="table-dark">
                    <tr>
                        <td colspan="3" class="text-end fw-bold">TOTAL POR COBRAR:</td>
                        <td colspan="2" class="fw-bold">Bs. {{ number_format($totalDeuda, 2) }}</td>
                    </tr>
                </tfoot>
                @endif
            </table>
        </div>
    </div>
    <div class="mt-3">
        <button onclick="window.print()" class="btn btn-outline-danger">Imprimir Lista de Deudores</button>
    </div>
</div>
@endsection