<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIDUMSS - Portal Propietario</title>
    
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Google Fonts: Source Sans Pro -->
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        /* Estilo Base Stellar */
        body {
            background-color: #935d8c;
            background-image: linear-gradient(45deg, #e37682 15%, #5f4d93 85%);
            background-attachment: fixed;
            font-family: 'Source Sans Pro', sans-serif;
            margin: 0;
            padding: 0;
            color: white;
            min-height: 100vh;
        }

        /* HEADER PRINCIPAL */
        #header-propietario {
            text-align: center;
            padding: 3rem 0 2rem 0;
        }

        #header-propietario h1 {
            font-size: 2.5rem;
            font-weight: 700;
            letter-spacing: 4px;
            text-transform: uppercase;
            margin-bottom: 0.5rem;
        }

        #header-propietario p {
            font-weight: 300;
            opacity: 0.9;
            letter-spacing: 1px;
        }

        /* NAVEGACIÓN SUPERIOR (Sticky) */
        #nav-propietario {
            background: rgba(255, 255, 255, 0.96);
            position: sticky;
            top: 0;
            z-index: 1000;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 0 20px;
        }

        #nav-propietario ul {
            list-style: none;
            display: flex;
            margin: 0;
            padding: 0;
            align-items: center;
        }

        #nav-propietario ul li a {
            display: block;
            padding: 1.3rem 1.5rem;
            color: #636363;
            text-decoration: none;
            font-weight: 600;
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: all 0.3s;
        }

        #nav-propietario ul li a:hover {
            color: #5f4d93;
            background: rgba(95, 77, 147, 0.05);
        }

        /* Info de Usuario en Nav */
        .nav-user-info {
            border-left: 1px solid #eee;
            padding-left: 20px;
            margin-left: 10px;
            display: flex;
            align-items: center;
            color: #636363;
        }

        /* CONTENEDOR DE TARJETA BLANCA */
        .main-wrapper {
            max-width: 1200px;
            margin: 3rem auto;
            padding: 0 20px;
        }

        .main-card {
            background: #ffffff;
            color: #636363;
            border-radius: 15px;
            padding: 50px;
            box-shadow: 0 15px 40px rgba(0,0,0,0.2);
            min-height: 60vh;
        }

        /* Botón Cerrar Sesión Estilizado */
        .btn-logout {
            background: transparent;
            border: 1px solid #e74c3c;
            color: #e74c3c;
            border-radius: 50px;
            padding: 5px 15px;
            font-size: 0.75rem;
            font-weight: bold;
            transition: 0.3s;
            margin-left: 15px;
        }

        .btn-logout:hover {
            background: #e74c3c;
            color: white;
        }

        /* Títulos de sección */
        h2 {
            color: #5f4d93;
            font-weight: 700;
            border-bottom: 2px solid #efefef;
            padding-bottom: 15px;
            margin-bottom: 30px;
        }
    </style>
</head>
<body>

    <!-- 1. Encabezado -->
    <header id="header-propietario">
        <h1>SIDUMSS</h1>
        <p>Urbanización Norte Plan "A" - Portal del Propietario</p>
    </header>

    <!-- 2. Menú Superior -->
    <nav id="nav-propietario">
        <ul>
            <li><a href="{{ route('propietario.dashboard') }}"><i class="fas fa-home me-2"></i> Inicio</a></li>
            <li><a href="{{ route('propietario.avisos') }}"><i class="fas fa-file-invoice-dollar me-2"></i> Mis Avisos</a></li>
            <li><a href="{{ route('propietario.reservas.index') }}"><i class="fas fa-calendar-alt me-2"></i> Reservas</a></li>
        </ul>

        <!-- Información del Usuario a la derecha -->
        <div class="nav-user-info d-none d-md-flex">
            <span class="small fw-bold me-2">{{ Auth::user()->nombre }} {{ Auth::user()->apellido_paterno }}</span>
            <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill small">Vecino</span>
            
            <form action="{{ route('logout') }}" method="POST" class="m-0">
                @csrf
                <button type="submit" class="btn-logout">Salir</button>
            </form>
        </div>
    </nav>

    <!-- 3. Contenido Principal -->
    <div class="main-wrapper">
        <div class="main-card">
            @yield('content')
        </div>
    </div>

    <!-- Footer -->
    <footer class="text-center py-5 opacity-75">
        <p>&copy; {{ date('Y') }} SIDUMSS Norte A. Gestión Residencial.</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>