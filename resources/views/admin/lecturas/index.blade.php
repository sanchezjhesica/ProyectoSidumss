@extends('layouts.admin')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Historial de Lecturas de Agua</h2>
        
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card shadow">
        <div class="card-body p-0">
            <table class="table table-striped mb-0">
                <thead>
                    <tr>
                        <th class="ps-3">Fecha</th>
                        <th>Nro Casa</th>
                        <th>Consumo (m³)</th>
                        <th>Estado</th>
                        <th class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($lecturas as $l)
                    <tr>
                        <td class="ps-3">{{ \Carbon\Carbon::parse($l->fecha_lectura)->format('d/m/Y') }}</td>
                        <td>{{ $l->vivienda->nro_casa }}</td>
                        <td class="fw-bold">{{ $l->lectura_actual - $l->lectura_anterior }} m³</td>
                        
                        <!-- LÓGICA DE ESTADO -->
                        <td>
                            @php
                                // Buscamos si esta lectura tiene un cobro para el mismo mes y año
                                $mesLectura = date('n', strtotime($l->fecha_lectura));
                                $anioLectura = date('Y', strtotime($l->fecha_lectura));

                                $cobro = $l->vivienda->cobros
                                    ->where('periodo_mes', $mesLectura)
                                    ->where('periodo_anio', $anioLectura)
                                    ->first();
                            @endphp

                            @if($cobro && $cobro->estado_pago == 'Pagado')
                                <span class="badge bg-success p-2">
                                    <i class="fas fa-check-circle"></i> Pagado
                                </span>
                            @else
                                {{-- Si no está pagado, mostramos un botón que al pulsarlo registre el pago --}}
                                <form action="{{ route('admin.cobros.pagar', $cobro->id_cobro ?? 0) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-danger shadow-sm" 
                                            onclick="return confirm('¿Confirmar pago de esta lectura?')">
                                        Sin Cancelar
                                    </button>
                                </form>
                            @endif
                        </td>

                        <td class="text-center">
                            <!-- BOTÓN VER RECIBO -->
                            <a href="{{ route('admin.lecturas.recibo', $l->id_lectura) }}" class="btn btn-sm btn-outline-primary">
                                Ver Recibo
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>

            </table>
        </div>
    </div>
</div>
@endsection