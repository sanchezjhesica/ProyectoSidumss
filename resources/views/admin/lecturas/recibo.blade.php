{{-- Detectamos el rol del usuario para poner el menú que corresponde --}}
@extends(Auth::user()->id_rol == 1 ? 'layouts.admin' : 'layouts.propietario')

@section('content')
<div class="container d-flex flex-column align-items-center mt-3">
    
    <!-- Este es el recuadro que se imprime -->
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
            <!-- Fila de Consumo Mes -->
<tr>
    <td class="label-bold">CONSUMO MES:</td>
    <td class="text-end px-2">
        {{-- Esto toma la fecha de la lectura y extrae el NOMBRE DEL MES y el AÑO --}}
        {{ strtoupper(\Carbon\Carbon::parse($lectura->fecha_lectura)->translatedFormat('F Y')) }}
    </td>
</tr>

<!-- Fila de Fecha de Lectura -->
<tr>
    <td class="label-bold">FECHA LECTURA:</td>
    <td class="text-end px-2">
        {{-- Esto formatea la fecha como 31-Ene-26 o 31/01/2026 --}}
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
                <td class="text-center">{{ strtoupper($lectura->vivienda->propietario->nombre ?? $propietario->nombre) }} {{ strtoupper($lectura->vivienda->propietario->apellido_paterno ?? $propietario->apellido_paterno) }}</td>
            </tr>

            <!-- Lecturas -->
            <tr>
                <td>LECT. ANT.</td>
                <td class="text-center">{{ number_format($lect_ant, 0) }}</td>
            </tr>
            <tr>
                <td>LECT. ACT.</td>
                <td class="text-center">{{ number_format($lect_act, 0) }}</td>
            </tr>
            <tr class="bg-light">
                <td class="label-bold">CONSUMO m3</td>
                <td class="text-center fw-bold">{{ $consumo_m3 }}</td>
            </tr>

            <!-- Desglose de Cobros -->
            <tr>
                <td class="label-bold">CONSUMO MES</td>
                <td class="text-end px-2">Bs {{ number_format($monto_consumo, 2) }}</td>
            </tr>
            <tr>
                <td>ALCANTARILLADO</td>
                <td class="text-end px-2">Bs {{ number_format($alcantarillado, 2) }}</td>
            </tr>
            <tr>
                <td>MORA 2 %</td>
                <td class="text-end px-2">Bs {{ number_format($mora ?? 0, 2) }}</td>
            </tr>

            {{-- FILA DINÁMICA DE WALLY --}}
            @if($monto_wally > 0)
            <tr>
                <td class="label-bold">WALLY</td>
                <td class="text-end px-2">Bs {{ number_format($monto_wally, 2) }}</td>
            </tr>
            @endif

            {{-- FILA DINÁMICA DE SALÓN DE EVENTOS --}}
            @if($monto_salon > 0)
            <tr>
                <td class="label-bold">SALÓN DE EVENTOS</td>
                <td class="text-end px-2">Bs {{ number_format($monto_salon, 2) }}</td>
            </tr>
            @endif

            <!-- Total -->
            <tr class="total-row">
                <td class="label-bold">TOTAL A PAGAR</td>
                <td class="text-end px-2 fw-bold fs-5">Bs {{ number_format($total, 2) }}</td>
            </tr>
        </table>
    </div>

    <!-- Botones fuera del recuadro para que no se impriman -->
    <div class="mt-4 no-print">
        <button onclick="window.print()" class="btn btn-primary btn-lg px-4">Imprimir Aviso</button>
        <!-- Busca el botón Cerrar y ponlo así -->
        <a href="{{ Auth::user()->id_rol == 1 ? route('admin.lecturas.index') : route('propietario.avisos') }}" 
        class="btn btn-secondary btn-lg px-4 no-print">
        Cerrar
        </a>
    </div>
</div>

<style>
    /* Estilos para que parezca una papeleta física */
    .recibo-container {
        width: 450px;
        background-color: #fff;
        border: 2px solid #000;
        padding: 0;
    }

    .tabla-recibo {
        width: 100%;
        border-collapse: collapse; /* Quita los espacios dobles entre bordes */
    }

    .tabla-recibo td {
        border: 1px solid #000; /* Bordes simples como en la foto */
        padding: 6px 8px; /* Espacio interno para que no esté pegado al borde */
        font-family: 'Arial', sans-serif;
        font-size: 14px;
        color: #000;
    }

    .label-bold {
        font-weight: bold;
    }

    .header-box {
        padding: 10px !important;
    }

    .total-row td {
        background-color: #f2f2f2;
        border-top: 2px solid #000;
    }

    /* Ocultar botones y menú al imprimir */
    @media print {
        .no-print, .sidebar, .navbar {
            display: none !important;
        }
        body {
            background-color: white !important;
        }
        .container {
            margin: 0 !important;
            padding: 0 !important;
        }
        .recibo-container {
            border: 1px solid #000;
            margin-top: 20px;
        }
    }
</style>
@endsection