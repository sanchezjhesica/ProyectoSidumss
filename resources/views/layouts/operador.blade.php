<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIDUMSS - Panel Operador</title>
    
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

        /* HEADER */
        #header-operador {
            text-align: center;
            padding: 3.5rem 0 2.5rem 0;
        }

        #header-operador h1 {
            font-size: 2.5rem;
            font-weight: 700;
            letter-spacing: 5px;
            text-transform: uppercase;
            margin-bottom: 0.5rem;
        }

        #header-operador p {
            font-weight: 300;
            opacity: 0.9;
            letter-spacing: 1px;
            font-size: 1.1rem;
        }

        /* NAVEGACIÓN SUPERIOR (Sticky) */
        #nav-operador {
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

        #nav-operador ul {
            list-style: none;
            display: flex;
            margin: 0;
            padding: 0;
            align-items: center;
        }

        #nav-operador ul li a {
            display: block;
            padding: 1.3rem 1.8rem;
            color: #636363;
            text-decoration: none;
            font-weight: 600;
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            transition: all 0.3s;
        }

        #nav-operador ul li a:hover {
            color: #5f4d93;
            background: rgba(95, 77, 147, 0.05);
        }

        /* Info de Usuario y Logout */
        .nav-user-info {
            border-left: 1px solid #eee;
            padding-left: 25px;
            margin-left: 15px;
            display: flex;
            align-items: center;
            color: #636363;
        }

        .btn-logout-operador {
            background: transparent;
            border: 1px solid #e74c3c;
            color: #e74c3c;
            border-radius: 50px;
            padding: 6px 18px;
            font-size: 0.75rem;
            font-weight: 700;
            transition: 0.3s;
            margin-left: 20px;
            text-decoration: none;
        }

        .btn-logout-operador:hover {
            background: #e74c3c;
            color: white;
        }

        /* CONTENEDOR DE TARJETA BLANCA */
        .main-wrapper {
            max-width: 1200px;
            margin: 3.5rem auto;
            padding: 0 25px;
        }

        .main-card {
            background: #ffffff;
            color: #636363;
            border-radius: 15px;
            padding: 55px;
            box-shadow: 0 20px 45px rgba(0,0,0,0.25);
            min-height: 60vh;
        }

        /* Títulos de sección internos */
        h2 {
            color: #5f4d93;
            font-weight: 700;
            border-bottom: 2px solid #efefef;
            padding-bottom: 15px;
            margin-bottom: 35px;
        }

        /* Footer */
        footer {
            text-align: center;
            padding: 4rem 0;
            color: rgba(255, 255, 255, 0.7);
            font-size: 0.9rem;
        }
    </style>
</head>
<body>

    <!-- 1. Header Hero -->
    <header id="header-operador">
        <h1>SIDUMSS</h1>
        <p>Módulo de Registro para Operadores</p>
    </header>

<nav id="nav-operador">
    <ul>
        <li><a href="{{ route('operador.dashboard') }}"><i class="fas fa-chart-line me-2"></i> Inicio</a></li>
        <li><a href="{{ route('operador.lecturas.crear') }}"><i class="fas fa-faucet me-2"></i> Registrar</a></li>
        
        <!-- Agregamos el cerrar sesión como un LI para que no falle el diseño -->
        <li>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
            <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" style="color: #e74c3c;">
                <i class="fas fa-sign-out-alt me-2"></i> SALIR
            </a>
        </li>
    </ul>
</nav>

    <div class="main-wrapper">
        <div class="main-card animate__animated animate__fadeIn">
            @yield('content')
        </div>
    </div>

    <footer>
        <p>&copy; {{ date('Y') }} Urbanización Sidumss Norte Plan "A"</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>