@extends('layouts.propietario')

@section('content')
<div class="propietario-stellar">
    <!-- 1. CABECERA DE SECCIÓN -->
    <div class="d-flex justify-content-between align-items-center mb-5 border-bottom pb-4">
        <div>
            <h2 class="text-purple mb-0"><i class="fas fa-file-invoice-dollar me-2"></i> Mis Avisos de Cobro</h2>
            <p class="text-muted mb-0">Consulte su historial de facturación, consumos de agua y estado de deudas.</p>
        </div>
    </div>

    <!-- 2. TABLA DE AVISOS (Estilo Stellar Card) -->
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4 py-3 uppercase-tracking">Periodo de Consumo</th>
                            <th class="py-3 uppercase-tracking">Monto Total</th>
                            <th class="py-3 uppercase-tracking text-center">Estado</th>
                            <th class="py-3 text-center uppercase-tracking">Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                    @forelse($avisos as $a)
                    <tr>
                        <td class="ps-4">
                            <div class="d-flex align-items-center">
                                <div class="period-icon me-3">
                                    <i class="far fa-calendar-alt"></i>
                                </div>
                                <span class="fw-bold text-dark fs-6">
                                    {{ \Carbon\Carbon::create()->month($a->periodo_mes)->translatedFormat('F') }} {{ $a->periodo_anio }}
                                </span>
                            </div>
                        </td>
                        <td>
                            <span class="fs-5 fw-bold text-dark">Bs. {{ number_format($a->total_pagar, 2) }}</span>
                        </td>
                        <td class="text-center">
                            @if($a->estado_pago == 'Pagado')
                                <span class="badge-stellar bg-success-soft text-success">
                                    <i class="fas fa-check-circle me-1"></i> Pagado
                                </span>
                            @else
                                <span class="badge-stellar bg-danger-soft text-danger">
                                    <i class="fas fa-clock me-1"></i> Pendiente
                                </span>
                            @endif
                        </td>
                        <td class="text-center">
                            @php
                                $lectura = Illuminate\Support\Facades\DB::table('lecturas')
                                    ->where('id_vivienda', $a->id_vivienda)
                                    ->whereMonth('fecha_lectura', $a->periodo_mes)
                                    ->whereYear('fecha_lectura', $a->periodo_anio)
                                    ->first();
                            @endphp

                            @if($lectura)
                                <a href="{{ route('compartido.recibo', $a->id_cobro) }}" class="btn btn-view-stellar shadow-sm">
                                    <i class="fas fa-eye me-2"></i> Ver Detalle
                                </a>
                            @else
                                <span class="text-muted small italic">Recibo en proceso</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center py-5">
                            <div class="mb-3 text-muted opacity-50">
                                <i class="fas fa-folder-open fa-3x"></i>
                            </div>
                            <p class="text-muted mb-0">Usted no cuenta con avisos de cobro registrados actualmente.</p>
                        </td>
                    </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<style>
    /* VARIABLES Y ESTILOS STELLAR */
    .text-purple { color: #5f4d93; }
    .bg-success-soft { background-color: rgba(40, 167, 69, 0.1); }
    .bg-danger-soft { background-color: rgba(220, 53, 69, 0.1); }
    
    .uppercase-tracking {
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 1.5px;
        font-weight: 700;
        color: #888;
    }

    /* Icono de Periodo */
    .period-icon {
        width: 35px;
        height: 35px;
        background: rgba(95, 77, 147, 0.05);
        color: #5f4d93;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.9rem;
    }

    /* Badges Estilo Stellar */
    .badge-stellar {
        display: inline-block;
        padding: 6px 16px;
        border-radius: 50px;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    /* Botón de Acción */
    .btn-view-stellar {
        background-color: white;
        border: 1px solid #5f4d93;
        color: #5f4d93;
        border-radius: 50px;
        padding: 6px 18px;
        font-size: 0.85rem;
        font-weight: 600;
        transition: all 0.3s;
    }
    .btn-view-stellar:hover {
        background-color: #5f4d93;
        color: white;
        transform: translateY(-2px);
    }

    .rounded-4 { border-radius: 1rem !important; }
    
    /* Animación para el empty state */
    .opacity-50 { opacity: 0.5; }
</style>
@endsection