@extends('layouts.admin')

@section('content')
<div class="lecturas-stellar">
    <!-- Cabecera de Página -->
    <div class="d-flex justify-content-between align-items-center mb-5 border-bottom pb-4">
        <div>
            <h2 class="text-purple mb-0"><i class="fas fa-faucet me-2"></i> Historial de Lecturas</h2>
            <p class="text-muted mb-0">Monitoreo detallado del consumo de agua y estado de pagos por vivienda.</p>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-stellar alert-dismissible fade show mb-4" role="alert">
            <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Contenedor de la Tabla Estilizada -->
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-stellar-light">
                        <tr>
                            <th class="ps-4 py-3 text-purple uppercase-tracking">Fecha</th>
                            <th class="py-3 text-purple uppercase-tracking">Nro Casa</th>
                            <th class="py-3 text-purple uppercase-tracking">Consumo</th>
                            <th class="py-3 text-purple uppercase-tracking">Estado de Pago</th>
                            <th class="py-3 text-center text-purple uppercase-tracking">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($lecturas as $l)
                        <tr>
                            <td class="ps-4 fw-bold">
                                <i class="far fa-calendar-alt text-muted me-2"></i>
                                {{ \Carbon\Carbon::parse($l->fecha_lectura)->format('d/m/Y') }}
                            </td>
                            <td>
                                <span class="badge bg-purple-soft text-purple px-3 py-2">
                                    Casa #{{ $l->vivienda->nro_casa }}
                                </span>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="consumption-drop me-2"><i class="fas fa-tint"></i></div>
                                    <span class="fw-bold text-dark">{{ $l->lectura_actual - $l->lectura_anterior }} m³</span>
                                </div>
                            </td>
                            
                            <td>
                                @php
                                    $mesLectura = date('n', strtotime($l->fecha_lectura));
                                    $anioLectura = date('Y', strtotime($l->fecha_lectura));
                                    $cobro = $l->vivienda->cobros
                                        ->where('periodo_mes', $mesLectura)
                                        ->where('periodo_anio', $anioLectura)
                                        ->first();
                                @endphp

                                @if($cobro && $cobro->estado_pago == 'Pagado')
                                    <span class="badge rounded-pill bg-success-soft text-success px-3 py-2">
                                        <i class="fas fa-check me-1"></i> Pagado
                                    </span>
                                @else
                                    @if($cobro)
                                        <form action="{{ route('admin.cobros.pagar', $cobro->id_cobro) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-pay-action" 
                                                    onclick="return confirm('¿Confirmar pago de esta lectura?')">
                                                <i class="fas fa-exclamation-circle me-1"></i> Sin Cancelar
                                            </button>
                                        </form>
                                    @else
                                        <span class="text-muted small italic">Cobro no generado</span>
                                    @endif
                                @endif
                            </td>

                            <td class="text-center">
                                @if($cobro)
                                    <a href="{{ route('compartido.recibo', $cobro->id_cobro) }}" class="btn btn-sm btn-view-recibo">
                                        <i class="fas fa-file-invoice-dollar me-1"></i> Recibo
                                    </a>
                                @else
                                    <span class="badge bg-light text-muted fw-normal">N/A</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<style>
    .text-purple { color: #5f4d93; }
    .bg-stellar-light { background-color: #fcfaff; }
    .bg-purple-soft { background-color: rgba(95, 77, 147, 0.08); }
    .bg-success-soft { background-color: rgba(40, 167, 69, 0.1); }
    
    .uppercase-tracking {
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 1px;
        font-weight: 700;
    }

    .table thead th { border-bottom: 2px solid #efefef; }
    .table tbody tr { transition: all 0.2s; }
    .table tbody tr:hover { background-color: #fdfdfd; }

    .consumption-drop {
        width: 25px;
        height: 25px;
        background: #e3f2fd;
        color: #2196f3;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.7rem;
    }

    .btn-pay-action {
        background-color: #fff1f0;
        color: #f5222d;
        border: 1px solid #ffa39e;
        border-radius: 50px;
        padding: 4px 12px;
        font-size: 0.8rem;
        font-weight: 600;
        transition: 0.3s;
    }
    .btn-pay-action:hover {
        background-color: #f5222d;
        color: white;
    }

    .btn-view-recibo {
        color: #5f4d93;
        border: 1px solid #5f4d93;
        border-radius: 50px;
        padding: 4px 15px;
        font-size: 0.8rem;
        font-weight: 600;
        transition: 0.3s;
    }
    .btn-view-recibo:hover {
        background-color: #5f4d93;
        color: white;
    }

    .alert-stellar {
        background: #fff;
        border-left: 5px solid #5f4d93;
        color: #5f4d93;
        box-shadow: 0 4px 12px rgba(0,0,0,0.05);
        border-radius: 8px;
    }

    .rounded-4 { border-radius: 1rem !important; }
</style>
@endsection