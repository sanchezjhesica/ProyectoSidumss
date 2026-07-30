<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin SIDUMSS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { display: flex; min-height: 100vh; }
        .sidebar { width: 250px; background: #63c288; color: white; }
        .content { flex: 1; padding: 20px; background: #faf8f8; }
        .sidebar a { color: white; text-decoration: none; padding: 10px 20px; display: block; }
        .sidebar a:hover { background: #495057; }
    </style>
</head>
<body>
    <div class="sidebar">
        <h3 class="p-3 text-center">SIDUMSS</h3>
<!-- Busca tu navegación en admin.blade.php y reemplaza el botón de Reportes -->
<nav>
    <a href="{{ route('admin.dashboard') }}">Inicio</a>
    <a href="{{ route('admin.usuarios.index') }}">Gestion de Usuarios</a>
    <a href="{{ route('admin.viviendas.index') }}">Gestion de Viviendas</a>
    <a href="{{ route('admin.lecturas.index') }}">Control Lecturas</a>

    <!-- SECCIÓN DE REPORTES CON SUBMENÚ -->
    <div class="menu-item">
        <div class="menu-title" style="padding: 10px 20px; color: #0a0a0a; font-size: 12px; text-uppercase; font-weight: bold;">REPORTES</div>
        <a href="{{ route('admin.reportes.general') }}" style="padding-left: 35px; font-size: 14px;">Reporte General </a>
        <a href="{{ route('admin.reportes.vivienda') }}" style="padding-left: 35px; font-size: 14px;">Reporte por Casas</a>
        <a href="{{ route('admin.reportes.morosidad') }}" style="padding-left: 35px; font-size: 14px;">Morosidad de Pagos</a>
    </div>

    <a href="{{ route('admin.tarifas.edit') }}">Configurarcion Tarifas</a>
</nav>
    </div>
    <div class="content">
        @yield('content')
    </div>
</body>
</html>