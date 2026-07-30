<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portal Propietario - SIDUMSS</title>
    <!-- 1. IMPORTANTE: Enlace a Bootstrap para el diseño -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { display: flex; min-height: 100vh; background-color: #f8f9fa; }
        .sidebar { width: 260px; background: #2c3e50; color: white; }
        .content { flex: 1; padding: 30px; }
        .sidebar a { color: #ecf0f1; text-decoration: none; padding: 12px 20px; display: block; border-bottom: 1px solid #34495e; }
        .sidebar a:hover { background: #34495e; color: #3498db; }
        .sidebar h3 { background: #1a252f; margin: 0; padding: 20px; font-size: 1.2rem; }
    </style>
</head>
<body>
    <div class="sidebar shadow">
        <h3 class="text-center fw-bold text-info">SIDUMSS <br><small class="text-white fw-light" style="font-size: 0.6em;">Portal Vecino</small></h3>
        <nav>
            <a href="{{ route('propietario.dashboard') }}">Inicio</a>
            <a href="{{ route('propietario.avisos') }}">Mis Avisos de Cobro</a>
            <a href="{{ route('propietario.reservas.index') }}">Reservar Áreas Comunes</a>
            <a href="/" class="text-danger mt-5">Cerrar Sesión</a>
        </nav>
    </div>

    <div class="content">
        <!-- 2. MUY IMPORTANTE: Aquí es donde se carga el formulario -->
        @yield('content')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>