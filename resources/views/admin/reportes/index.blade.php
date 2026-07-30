@extends('layouts.admin')
@section('content')
<div class="container">
    <h2 class="mb-4">Reporte General Sidumss Norte A</h2>
    
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card border-success shadow">
                <div class="card-body text-center">
                    <h6 class="text-success">Total Ingresos</h6>
                    <h4>Bs. {{ number_format($totalIngresos, 2) }}</h4>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-danger shadow">
                <div class="card-body text-center">
                    <h6 class="text-danger">Total Egresos</h6>
                    <h4>Bs. {{ number_format($totalEgresos, 2) }}</h4>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-warning shadow">
                <div class="card-body text-center">
                    <h6 class="text-warning">Deuda por Cobrar</h6>
                    <h4>Bs. {{ number_format($deudaPendiente, 2) }}</h4>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-info shadow">
                <div class="card-body text-center">
                    <h6 class="text-info">Consumo Agua Total</h6>
                    <h4>{{ $consumoTotal }} m³</h4>
                </div>
            </div>
        </div>
    </div>
    <!-- SECCIÓN DE DETALLES DETALLADOS -->
    <div class="row mt-5 no-print-break">
        <!-- TABLA DE INGRESOS POR CASA -->
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">Detalle de Ingresos (Cobros)</h5>
                </div>
                <div class="card-body p-0">
                    <table class="table table-sm table-striped mb-0">
                        <thead>
                            <tr>
                                <th>Fecha</th>
                                <th>Casa</th>
                                <th>Propietario</th>
                                <th class="text-end">Monto</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($detallesIngresos as $ing)
                            <tr>
                                <td>{{ \Carbon\Carbon::parse($ing->fecha_pago)->format('d/m/y') }}</td>
                                <td>{{ $ing->vivienda->nro_casa }}</td>
                                <td class="small">{{ $ing->vivienda->propietarios->first()->nombre ?? 'S/N' }}</td>
                                <td class="text-end fw-bold">Bs. {{ number_format($ing->total_pagar, 2) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- TABLA DE EGRESOS (GASTOS) -->
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-header bg-danger text-white">
                    <h5 class="mb-0">Detalle de Egresos (Gastos)</h5>
                </div>
                <div class="card-body p-0">
                    <table class="table table-sm table-striped mb-0">
                        <thead>
                            <tr>
                                <th>Fecha</th>
                                <th>Concepto</th>
                                <th>Categoría</th>
                                <th class="text-end">Monto</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($detallesEgresos as $egr)
                            <tr>
                                <td>{{ \Carbon\Carbon::parse($egr->fecha_pago)->format('d/m/y') }}</td>
                                <td class="small">{{ $egr->descripcion }}</td>
                                <td><span class="badge bg-light text-dark border">{{ $egr->categoria }}</span></td>
                                <td class="text-end fw-bold text-danger">Bs. {{ number_format($egr->monto, 2) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow">
        <div class="card-header bg-dark text-white">Resumen de Caja</div>
        <div class="card-body">
            <h3>Saldo Neto en Caja: Bs. {{ number_format($totalIngresos - $totalEgresos, 2) }}</h3>
            <p class="text-muted">Este monto representa el efectivo real disponible restando los gastos de los cobros pagados.</p>
            <button onclick="window.print()" class="btn btn-outline-primary">Imprimir Reporte</button>
        </div>
    </div>

    
</div>
<style>
    /* ESTILOS PARA PANTALLA (Normal) */
    .btn-print { margin-bottom: 20px; }

    /* ESTILOS EXCLUSIVOS PARA IMPRESIÓN */
    @media print {
        /* 1. Ocultar el menú lateral, botones y cualquier elemento innecesario */
        .sidebar, .navbar, .btn, .no-print, footer {
            display: none !important;
        }

        /* 2. Ajustar el contenedor principal para que use todo el ancho de la hoja */
        .content, .container, main {
            margin: 0 !important;
            padding: 0 !important;
            width: 100% !important;
            max-width: 100% !important;
            position: absolute !important;
            left: 0 !important;
            top: 0 !important;
        }

        /* 3. Forzar que los colores de fondo se impriman (tarjetas de colores) */
        .card {
            border: 1px solid #ececec !important;
            box-shadow: none !important;
            break-inside: avoid; /* Evita que una tarjeta se parta entre dos hojas */
        }
        
        .bg-success, .bg-danger, .bg-warning, .bg-info {
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
            color: #000 !important; /* Texto negro para mejor lectura en papel */
        }

        /* 4. Ajustar las tablas para que ocupen el ancho total */
        table {
            width: 100% !important;
            border: 1px solid #000 !important;
        }

        th, td {
            font-size: 12px !important; /* Letra un poco más pequeña para papel */
        }

        /* 5. Título del reporte centrado y grande */
        h2 {
            text-align: center;
            margin-bottom: 30px;
            color: #000 !important;
        }
    }
</style>
@endsection