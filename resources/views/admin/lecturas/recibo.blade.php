{{-- Detectamos el rol del usuario para poner el menú que corresponde --}}
@extends(Auth::user()->id_rol == 1 ? 'layouts.admin' : 'layouts.propietario')

@section('content')
<div class="recibo-stellar-view">
    
    <!-- Título fuera de la zona de impresión -->
    <div class="text-center mb-4 no-print">
        <h2 class="text-purple fw-bold"><i class="fas fa-file-invoice-dollar me-2"></i> Aviso de Cobro</h2>
        <p class="text-muted">Revise los detalles del consumo de agua y cargos adicionales.</p>
    </div>

    <!-- CONTENEDOR DEL RECIBO (Lo que se imprime) -->
    <div class="recibo-container shadow-lg mx-auto">
        <table class="tabla-recibo">
            <!-- Encabezado con Logo Estilizado -->
            <thead>
                <tr>
                    <th colspan="2" class="header-box">
                        <div class="d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center">
                                <img src="{{ asset('img/logo.jpg') }}" alt="Logo" class="recibo-logo me-3">
                                <div class="text-start">
                                    <div class="fw-bold fs-6">URBANIZACION SIDUMSS NORTE</div>
                                    <div class="small text-uppercase tracking-wider">Plan "A" - Cochabamba</div>
                                </div>
                            </div>
                            <div class="text-end">
                                <div class="badge bg-purple-stellar text-white px-3 py-2">RECIBO OFICIAL</div>
                            </div>
                        </div>
                    </th>
                </tr>
            </thead>

            <tbody>
                <!-- Información Temporal -->
                <tr class="bg-stellar-soft">
                    <td class="label-bold py-3"><i class="far fa-calendar-check me-2"></i>CONSUMO DEL MES:</td>
                    <td class="text-end px-3 fw-bold text-purple">
                        {{ strtoupper(\Carbon\Carbon::parse($lectura->fecha_lectura)->translatedFormat('F Y')) }}
                    </td>
                </tr>
                <tr>
                    <td class="label-muted small ps-4">FECHA DE EMISIÓN:</td>
                    <td class="text-end px-3 small text-muted">
                        {{ \Carbon\Carbon::parse($lectura->fecha_lectura)->format('d/m/Y') }}
                    </td>
                </tr>

                <!-- Datos del Propietario -->
                <tr>
                    <td class="label-bold ps-4">Nº DE CASA:</td>
                    <td class="text-center fw-bold fs-5">#{{ $lectura->vivienda->numero_casa ?? $lectura->vivienda->nro_casa }}</td>
                </tr>
                <tr>
                    <td class="label-bold ps-4">PROPIETARIO / RESIDENTE:</td>
                    <td class="text-center text-uppercase">
                        {{ $propietario ? ($propietario->nombre . ' ' . $propietario->apellido_paterno) : 'SIN ASIGNAR' }}
                    </td>
                </tr>

                <!-- Bloque de Lecturas y Consumo -->
                <tr class="bg-light">
                    <td colspan="2" class="py-1"></td>
                </tr>
                <tr>
                    <td class="ps-4">LECTURA ANTERIOR:</td>
                    <td class="text-end px-3">{{ number_format($lect_ant, 2) }} m³</td>
                </tr>
                <tr>
                    <td class="ps-4">LECTURA ACTUAL:</td>
                    <td class="text-end px-3">{{ number_format($lect_act, 2) }} m³</td>
                </tr>
                <tr class="fw-bold border-top border-bottom">
                    <td class="ps-4 text-purple">CONSUMO TOTAL m³:</td>
                    <td class="text-end px-3 text-purple fs-5">{{ number_format($consumo_m3, 2) }} m³</td>
                </tr>

                <!-- Detalles de Cobro -->
                <tr>
                    <td class="ps-4">Consumo de Agua del Mes:</td>
                    <td class="text-end px-3">Bs {{ number_format($monto_consumo, 2) }}</td>
                </tr>
                <tr>
                    <td class="ps-4">Servicio de Alcantarillado:</td>
                    <td class="text-end px-3">Bs {{ number_format($alcantarillado, 2) }}</td>
                </tr>
                <tr>
                    <td class="ps-4 border-bottom">Mantenimiento Fijo:</td>
                    <td class="text-end px-3 border-bottom">Bs {{ number_format($mantenimiento, 2) }}</td>
                </tr>

                {{-- Extras (Mora / Reservas) --}}
                @if(($mora ?? 0) > 0)
                <tr class="text-danger small">
                    <td class="ps-4 italic">Mora 2% (Deuda Pendiente):</td>
                    <td class="text-end px-3">Bs {{ number_format($mora, 2) }}</td>
                </tr>
                @endif

                @if($monto_wally > 0 || $monto_salon > 0)
                <tr class="text-primary small">
                    <td class="ps-4 italic">Reservas (Wally/Salón):</td>
                    <td class="text-end px-3">Bs {{ number_format($monto_wally + $monto_salon, 2) }}</td>
                </tr>
                @endif

                <!-- TOTAL FINAL -->
                <tr class="total-row-stellar">
                    <td class="fw-bold ps-4 fs-5 text-white">TOTAL A CANCELAR:</td>
                    <td class="text-end pe-3 fw-bold fs-4 text-white">
                        Bs {{ number_format($total, 2) }}
                    </td>
                </tr>
            </tbody>
        </table>
        
        <div class="footer-recibo p-3 text-center small text-muted border-top">
            Favor de realizar el pago en oficinas administrativas o via transferencia. <br>
            <strong>"Cuidar el agua es compromiso de todos"</strong>
        </div>
    </div>

    <!-- BOTONES STELLAR (No se imprimen) -->
    <div class="mt-5 no-print d-flex justify-content-center gap-3">
        <button onclick="window.print()" class="btn btn-purple-stellar btn-lg px-5">
            <i class="fas fa-print me-2"></i> Imprimir Aviso
        </button>
        <a href="{{ Auth::user()->id_rol == 1 ? route('admin.lecturas.index') : route('propietario.avisos') }}" 
           class="btn btn-outline-secondary btn-lg px-5 rounded-pill">
            <i class="fas fa-times me-2"></i> Cerrar
        </a>
    </div>
</div>

<style>
    .text-purple { color: #5f4d93; }
    .bg-stellar-soft { background-color: rgba(95, 77, 147, 0.05); }
    .bg-purple-stellar { background-color: #5f4d93; }
    
    .tracking-wider { letter-spacing: 1.5px; }

    .recibo-container {
        width: 100%;
        max-width: 550px;
        background-color: #fff;
        border: 1px solid #e0e0e0;
        border-radius: 12px;
        overflow: hidden;
    }

    .recibo-logo {
        width: 50px;
        height: 50px;
        border-radius: 8px;
        object-fit: cover;
    }

    .tabla-recibo {
        width: 100%;
        border-collapse: collapse;
    }

    .tabla-recibo td, .tabla-recibo th {
        padding: 12px 15px;
        color: #444;
        border-bottom: 1px solid #f0f0f0;
    }

    .label-bold { font-weight: 700; color: #5f4d93; }
    .header-box { background: #fff; border-bottom: 2px solid #5f4d93 !important; }

    .total-row-stellar td {
        background-color: #5f4d93;
        padding: 20px 15px;
        border: none;
    }

    .btn-purple-stellar {
        background: #5f4d93;
        color: white;
        border-radius: 50px;
        font-weight: bold;
        border: none;
        transition: 0.3s;
    }
    .btn-purple-stellar:hover { background: #4a3b75; color: white; transform: translateY(-2px); }

    @media print {
        @page { size: auto; margin: 0mm; }
        .no-print, nav, #header-stellar, .sidebar { display: none !important; }
        
        body { background: white !important; margin: 0; padding: 20px; }
        
        .main-card { box-shadow: none !important; padding: 0 !important; }
        
        .recibo-container {
            width: 100%;
            max-width: 100%;
            border: 2px solid #000;
            border-radius: 0;
            box-shadow: none !important;
        }

        .tabla-recibo td, .tabla-recibo th {
            border: 1px solid #000 !important;
            color: #000 !important;
        }

        .total-row-stellar td {
            background-color: #eee !important;
            color: #000 !important;
            border-top: 2px solid #000 !important;
        }

        .bg-purple-stellar, .text-purple { color: #000 !important; background: transparent !important; }
    }
</style>
@endsection