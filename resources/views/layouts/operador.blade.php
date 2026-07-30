<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Operador SIDUMSS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { display: flex; min-height: 100vh; }
        .sidebar { width: 250px; background: #3b82f6; color: white; } /* Cambié a azul para diferenciarlo */
        .content { flex: 1; padding: 20px; background: #faf8f8; }
        .sidebar a { color: white; text-decoration: none; padding: 10px 20px; display: block; }
        .sidebar a:hover { background: #1d4ed8; }
    </style>
</head>
<body>
    <div class="sidebar">
        <h3 class="p-3 text-center">SIDUMSS <br><small>Operador</small></h3>
        <nav>
            <a href="{{ route('operador.dashboard') }}">Inicio</a>
            <a href="{{ route('operador.lecturas.crear') }}">Registrar Lectura</a>
            <a href="{{ route('operador.averias.index') }}">Reportar Medidor Mal</a>
            <hr>
            <a href="/">Cerrar Sesión</a>
        </nav>
    </div>
    <div class="content">
        @yield('content')
    </div>
</body>
</html>