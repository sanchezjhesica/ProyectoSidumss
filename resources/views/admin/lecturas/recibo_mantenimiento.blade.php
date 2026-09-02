<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Recibo de Agua - Casa {{ $cobro->vivienda->nro_casa }}</title>
    <style>
        body { font-family: 'Segoe UI', sans-serif; color: #333; }
        .recibo-wrapper { max-width: 700px; margin: auto; border: 1px solid #ddd; padding: 30px; position: relative; }
        .header { text-align: center; border-bottom: 2px solid #5f4d93; padding-bottom: 10px; }
        .header h1 { color: #5f4d93; margin: 0; }
        .info-table { width: 100%; margin-top: 20px; }
        .section-title { background: #5f4d93; color: white; padding: 5px 10px; margin-top: 20px; font-weight: bold; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        table th, table td { border: 1px solid #eee; padding: 10px; text-align: left; }
        .total-row { font-size: 1.4rem; font-weight: bold; color: #5f4d93; text-align: right; margin-top: 20px; }
        .btn-box { text-align: center; margin-top: 20px; }
        .btn { padding: 10px 20px; border-radius: 20px; border: none; cursor: pointer; font-weight: bold; }
        .btn-print { background: #5f4d93; color: white; }
        .btn-close { background: #eee; color: #333; margin-left: 10px; }
        @media print { .no-print { display: none !important; } .recibo-wrapper { border: none; } }
    </style>
</head>
<body>
<!-- Reutiliza el mismo <style> del recibo de agua -->
<div class="recibo-wrapper">
    <div class="header">
        <h1>SIDUMSS NORTE A</h1>
        <p>RECIBO: <b>MANTENIMIENTO FIJO</b></p>
    </div>

    <table class="info-table">
        <tr>
            <td><b>Propietario:</b> {{ $cobro->vivienda->propietario->nombre }} {{ $cobro->vivienda->propietario->apellido_paterno }}</td>
            <td style="text-align: right;"><b>Periodo:</b> {{ $cobro->mes }}/{{ $cobro->anio }}</td>
        </tr>
    </table>

    <div class="section-title">DETALLE DEL CARGO</div>
    <table>
        <tr>
            <th>Descripción</th>
            <th style="text-align: right;">Monto</th>
        </tr>
        <tr>
            <td>Cuota fija mensual de mantenimiento (Limpieza, alumbrado, seguridad común)</td>
            <td style="text-align: right;">Bs. {{ number_format($cobro->monto_fijo, 2) }}</td>
        </tr>
    </table>

    <div class="total-row" style="margin-top: 50px;">
        TOTAL MANTENIMIENTO: Bs. {{ number_format($cobro->monto_fijo, 2) }}
    </div>
</div>
</body>
</html>