<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portal Propietario - SIDUMSS</title>
    <!-- 1. Enlace a Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome para iconos (opcional) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body { display: flex; min-height: 100vh; background-color: #f8f9fa; }
        .sidebar { width: 260px; background: #2c3e50; color: white; position: fixed; height: 100vh; }
        .content { flex: 1; margin-left: 260px; padding: 30px; }
        .sidebar a { color: #ecf0f1; text-decoration: none; padding: 12px 20px; display: block; border-bottom: 1px solid #34495e; }
        .sidebar a:hover { background: #34495e; color: #3498db; }
        .user-info { background: #1a252f; padding: 20px; text-align: center; border-bottom: 1px solid #34495e; }
    </style>
</head>
<body>
    <div class="sidebar shadow">
        <!-- SECCIÓN DE USUARIO DINÁMICA -->
        <div class="user-info">
            <div class="mb-2">
                <i class="fas fa-user-circle fa-3x text-info"></i>
            </div>
            <h6 class="mb-0 text-uppercase">{{ Auth::user()->nombre }}</h6>
            <small class="text-muted">{{ Auth::user()->apellido_paterno }}</small>
            <div class="mt-2">
                <span class="badge bg-primary" style="font-size: 0.7em;">VECINO SIDUMSS</span>
            </div>
        </div>

        <nav>
            <a href="{{ route('propietario.dashboard') }}"><i class="fas fa-home me-2"></i> Inicio</a>
            <a href="{{ route('propietario.avisos') }}"><i class="fas fa-file-invoice-dollar me-2"></i> Mis Avisos de Cobro</a>
            <a href="{{ route('propietario.reservas.index') }}"><i class="fas fa-calendar-alt me-2"></i> Reservar Áreas</a>
            
            <!-- BOTÓN DE CERRAR SESIÓN (FORMULARIO POST POR SEGURIDAD) -->
            <div class="px-3 mt-5">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-danger w-100 btn-sm shadow">
                        <i class="fas fa-sign-out-alt"></i> Cerrar Sesión
                    </button>
                </form>
            </div>
        </nav>
    </div>

    <div class="content">
        @yield('content')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>