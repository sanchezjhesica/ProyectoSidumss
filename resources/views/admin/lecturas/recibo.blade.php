{{-- Detectamos el rol del usuario para poner el menú que corresponde --}}
@extends(Auth::user()->id_rol == 1 ? 'layouts.admin' : 'layouts.propietario')

@section('content')
<div class="container d-flex flex-column align-items-center mt-3">
    
    <!-- ESTE ES EL RECUADRO QUE SE IMPRIME (Borde Negro) -->
    <div class="recibo-container">
        <table class="tabla-recibo">
            <!-- Encabezado con Logo -->
            <tr>
                <td colspan="2" class="header-box">
                    <div class="d-flex align-items-center">
                        <img src="{{ asset('img/logo.jpg') }}" alt="Logo" style="width: 60px; height: auto; margin-right: 15px;">
                        <div class="text-center w-100">
                            <div class="fw-bold">PROPIETARIO</div>
                            <div class="fw-bold small">URBANIZACION SIDUMSS NORTE PLAN "A"</div>
                        </div>
                    </div>
                </td>
            </tr>

            <!-- Periodo y Fecha -->
            <tr>
                <td class="label-bold">CONSUMO MES:</td>
                <td class="text-end px-2">
                    {{ strtoupper(\Carbon\Carbon::parse($lectura->fecha_lectura)->translatedFormat('F Y')) }}
                </td>
            </tr>
            <tr>
                <td class="label-bold">FECHA LECTURA:</td>
                <td class="text-end px-2">
                    {{ \Carbon\Carbon::parse($lectura->fecha_lectura)->format('d-M-y') }}
                </td>
            </tr>

            <!-- Datos de la Vivienda y Propietario -->
            <tr>
                <td class="label-bold" width="50%">Nº CASA</td>
                <td class="text-center">{{ $lectura->vivienda->numero_casa ?? $lectura->vivienda->nro_casa }}</td>
            </tr>
            <tr>
                <td class="label-bold">PROPIETARIO/INQUILINO</td>
                <td class="text-center">
                    {{ $propietario ? strtoupper($propietario->nombre . ' ' . $propietario->apellido_paterno) : 'SIN ASIGNAR' }}
                </td>
            </tr>

            <!-- Lecturas -->
            <tr>
                <td>LECT. ANT.</td>
                <td class="text-center">{{ number_format($lect_ant, 2) }}</td>
            </tr>
            <tr>
                <td>LECT. ACT.</td>
                <td class="text-center">{{ number_format($lect_act, 2) }}</td>
            </tr>
            <tr class="bg-light">
                <td class="label-bold">CONSUMO m3</td>
                <td class="text-center fw-bold">{{ number_format($consumo_m3, 2) }}</td>
            </tr>

            <!-- Sección de montos detallados -->
            <tr>
                <td class="label-bold">CONSUMO MES (Agua)</td>
                <td class="text-end">Bs {{ number_format($monto_consumo, 2) }}</td>
            </tr>
            <tr>
                <td>ALCANTARILLADO</td>
                <td class="text-end">Bs {{ number_format($alcantarillado, 2) }}</td>
            </tr>
            <tr>
                <td>MANTENIMIENTO FIJO</td>
                <td class="text-end">Bs {{ number_format($mantenimiento, 2) }}</td>
            </tr>

            {{-- Lógica de Mora: solo aparece si es mayor a 0 --}}
            @if(($mora ?? 0) > 0)
            <tr class="text-danger fw-bold">
                <td>MORA 2% (DEUDA ANTERIOR)</td>
                <td class="text-end">Bs {{ number_format($mora, 2) }}</td>
            </tr>
            @endif

            {{-- SUBDIVISIÓN DE RESERVAS --}}
            @if($monto_wally > 0)
            <tr>
                <td class="fw-bold text-primary">RESERVA WALLY</td>
                <td class="text-end">Bs {{ number_format($monto_wally, 2) }}</td>
            </tr>
            @endif

            @if($monto_salon > 0)
            <tr>
                <td class="fw-bold text-primary">RESERVA SALÓN DE EVENTOS</td>
                <td class="text-end">Bs {{ number_format($monto_salon, 2) }}</td>
            </tr>
            @endif

            <!-- Fila de Total Final -->
           <tr class="total-row">
                <td class="fw-bold">TOTAL A PAGAR</td>
                <td class="text-end fw-bold fs-5">
                    {{-- Ahora mostrará Bs 287.20 (que es la suma correcta) --}}
                    Bs {{ number_format($total, 2) }}
                </td>
            </tr>
        </table>
    </div> <!-- AQUÍ TERMINA EL RECIBO-CONTAINER -->

    <!-- BOTONES FUERA DEL RECUADRO PARA QUE NO SE IMPRIMAN -->
    <div class="mt-4 no-print d-flex gap-2">
        <button onclick="window.print()" class="btn btn-primary btn-lg px-4 shadow">
            <i class="fas fa-print"></i> Imprimir Aviso
        </button>
        <a href="{{ Auth::user()->id_rol == 1 ? route('admin.lecturas.index') : route('propietario.avisos') }}" 
           class="btn btn-secondary btn-lg px-4 shadow">
            Cerrar
        </a>
    </div>
</div>

<style>
    /* Estilos para la papeleta física */
    .recibo-container {
        width: 450px;
        background-color: #fff;
        border: 2px solid #000;
        padding: 0;
        box-shadow: 0 0 10px rgba(0,0,0,0.1);
    }

    .tabla-recibo {
        width: 100%;
        border-collapse: collapse;
    }

    .tabla-recibo td {
        border: 1px solid #000;
        padding: 6px 8px;
        font-family: 'Arial', sans-serif;
        font-size: 14px;
        color: #000;
    }

    .label-bold { font-weight: bold; }
    .header-box { padding: 10px !important; border-bottom: 2px solid #000 !important; }

    .total-row td {
        background-color: #f2f2f2;
        border-top: 2px solid #000;
        padding: 10px 8px;
    }

    /* Ocultar elementos al imprimir */
    @media print {
        .no-print, .sidebar {
            display: none !important;
        }
        body {
            background-color: white !important;
        }
        .container {
            margin: 0 !important;
            padding: 0 !important;
            width: 100% !important;
        }
        .recibo-container {
            border: 1px solid #000;
            box-shadow: none;
            margin: 0 auto;
        }
    }
</style>
@endsection