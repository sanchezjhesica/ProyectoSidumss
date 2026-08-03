<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Operador SIDUMSS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* ESTO ES LO MÁS IMPORTANTE */
        body { display: flex; min-height: 100vh; overflow-x: hidden; }
        .sidebar { width: 250px; background: #3b82f6; color: white; min-height: 100vh; position: fixed; }
        .main-content { flex: 1; margin-left: 250px; padding: 20px; background: #f4f7f6; width: calc(100% - 250px); }
        .sidebar a { color: white; text-decoration: none; padding: 15px 20px; display: block; border-bottom: 1px solid #2563eb; }
        .sidebar a:hover { background: #2563eb; }
    </style>
</head>
<body>
    <!-- LADO IZQUIERDO: MENÚ -->
    <div class="sidebar">
        <h3 class="p-3 text-center">SIDUMSS <br><small>Operador</small></h3>
        <nav>
            <a href="{{ route('operador.dashboard') }}">Inicio</a>
            <a href="{{ route('operador.lecturas.crear') }}">Registrar Lectura</a>
            <a href="/" class="mt-5 text-warning">Cerrar Sesión</a>
        </nav>
    </div>

    <!-- LADO DERECHO: CONTENIDO -->
    <div class="main-content">
        @yield('content')
    </div>
</body>
</html>