@extends('layouts.propietario')

@section('content')
<div class="container">
    <h2 class="mb-4">Mis Avisos de Cobro</h2>

    <div class="card shadow-sm">
        <div class="card-body p-0">
            <table class="table table-hover mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-3">Periodo</th>
                        <th>Monto Total</th>
                        <th>Estado</th>
                        <th class="text-center">Acción</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($avisos as $a)
                <tr>
                    <td class="ps-3">{{ $a->periodo_mes }}/{{ $a->periodo_anio }}</td>
                    <td class="fw-bold">
                        Bs. {{ number_format($a->total_pagar, 2) }}
                    </td>
                    <td>
                        @if($a->estado_pago == 'Pagado')
                            <span class="badge bg-success">Pagado</span>
                        @else
                            <span class="badge bg-danger">Pendiente</span>
                        @endif
                    </td>
                    <td class="text-center">
                        @php
                            // Buscamos la lectura que coincide con el periodo del cobro
                            $lectura = Illuminate\Support\Facades\DB::table('lecturas')
                                ->where('id_vivienda', $a->id_vivienda)
                                ->whereMonth('fecha_lectura', $a->periodo_mes)
                                ->whereYear('fecha_lectura', $a->periodo_anio)
                                ->first();
                        @endphp

                        @if($lectura)
                            <a href="{{ route('compartido.recibo', $a->id_cobro) }}" class="btn btn-sm btn-primary">
                                <i class="fas fa-file-invoice"></i> Ver Detalle
                            </a>
                        @else
                            <span class="text-muted small italic">Recibo no generado</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="text-center py-4 text-muted">Usted no cuenta con avisos de cobro registrados.</td>
                </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Estilo para ajustar el diseño igual a tu captura --}}
<style>
    .table thead th { border-top: none; color: #666; font-size: 0.9rem; }
    .badge { padding: 0.5em 0.8em; }
    .btn-sm { font-size: 0.85rem; }
</style>
@endsection