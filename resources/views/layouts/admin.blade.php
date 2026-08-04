<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIDUMSS - Panel de Administración</title>
    
    <!-- Bootstrap 5 y Font Awesome -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"> 
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700" rel="stylesheet">
    
    <style>
        :root {
            --stellar-grad: linear-gradient(45deg, #e37682 15%, #5f4d93 85%);
            --stellar-purple: #5f4d93;
            --stellar-text: #636363;
            --stellar-light: #f4f4f4;
            --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        body {
            background-color: #935d8c;
            background-image: var(--stellar-grad);
            background-attachment: fixed;
            font-family: 'Source Sans Pro', sans-serif;
            margin: 0;
            padding: 0;
            color: white;
            min-height: 100vh;
            -webkit-font-smoothing: antialiased;
        }

        #header-stellar {
            text-align: center;
            padding: 5rem 0 3.5rem 0;
        }
        
        #header-stellar h1 {
            font-size: 3rem;
            font-weight: 700;
            letter-spacing: 0.5rem;
            margin: 0;
            text-transform: uppercase;
            text-shadow: 0 10px 20px rgba(0,0,0,0.2);
        }

        #header-stellar p {
            font-weight: 300;
            letter-spacing: 2px;
            opacity: 0.8;
            margin-top: 10px;
        }

        #main-nav {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px); /* Efecto de desenfoque moderno */
            border-bottom: 1px solid rgba(255, 255, 255, 0.3);
            position: sticky;
            top: 0;
            z-index: 10000;
            box-shadow: 0 4px 30px rgba(0,0,0,0.1);
        }

        #main-nav ul {
            display: flex;
            justify-content: center;
            list-style: none;
            margin: 0;
            padding: 0;
            flex-wrap: wrap; 
        }

        #main-nav ul li {
            position: relative;
        }

        #main-nav ul li a {
            display: block;
            padding: 1.5rem 1.2rem;
            color: var(--stellar-text);
            text-decoration: none;
            font-weight: 600;
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.2rem;
            transition: var(--transition);
        }

        #main-nav ul li a:hover {
            color: var(--stellar-purple);
            background: rgba(95, 77, 147, 0.05);
        }

        .nav-dropdown:hover .dropdown-content {
            display: block;
            animation: fadeIn 0.3s ease;
        }

        .dropdown-content {
            display: none;
            position: absolute;
            background-color: #ffffff;
            min-width: 220px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.15);
            top: 100%;
            left: 50%;
            transform: translateX(-50%);
            border-radius: 0 0 12px 12px;
            padding: 10px 0;
            border: 1px solid #eee;
        }

        .dropdown-content a {
            padding: 0.8rem 1.8rem !important;
            text-transform: none !important;
            letter-spacing: 0.5px !important;
            font-size: 0.9rem !important;
            color: var(--stellar-text) !important;
            border-bottom: none !important;
        }

        .dropdown-content a:hover {
            background-color: #f8f9fa !important;
            color: var(--stellar-purple) !important;
            padding-left: 2.2rem !important; /* Efecto de desplazamiento al hover */
        }

        .logout-item a {
            color: #e74c3c !important;
            border-left: 1px solid #eee;
        }

        .main-wrapper {
            max-width: 1200px;
            margin: 4rem auto;
            padding: 0 20px;
            animation: slideUp 0.8s ease;
        }

        .main-card {
            background: #ffffff;
            color: var(--stellar-text);
            border-radius: 16px;
            padding: 60px;
            box-shadow: 0 30px 60px rgba(0,0,0,0.25);
            min-height: 60vh;
            border: 1px solid rgba(255,255,255,0.1);
        }

        h2 {
            color: var(--stellar-purple);
            font-weight: 700;
            font-size: 1.8rem;
            border-bottom: 2px solid #f0f0f0;
            padding-bottom: 15px;
            margin-bottom: 40px;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        @media (max-width: 768px) {
            #header-stellar { padding: 3rem 0 2rem 0; }
            #header-stellar h1 { font-size: 2rem; letter-spacing: 0.3rem; }
            
            #main-nav ul li a {
                padding: 1rem 0.8rem;
                letter-spacing: 0.1rem;
                font-size: 0.7rem;
            }

            .main-card {
                padding: 40px 20px;
                border-radius: 12px;
            }

            .logout-item a { border-left: none; }
        }
    </style>
</head>
<body>

    <header id="header-stellar">
        <h1>SIDUMSS</h1>
        <p>Sistema de Gestión</p>
    </header>

    <nav id="main-nav">
        <ul>
            <li><a href="{{ route('admin.dashboard') }}">Inicio</a></li>
            <li><a href="{{ route('admin.usuarios.index') }}">Usuarios</a></li>
            <li><a href="{{ route('admin.viviendas.index') }}">Viviendas</a></li>
            <li><a href="{{ route('admin.lecturas.index') }}">Lecturas</a></li>
            <li class="nav-dropdown">
                <a href="#">Reportes <i class="fas fa-chevron-down ms-1" style="font-size: 0.6rem;"></i></a>
                <div class="dropdown-content">
                    <a href="{{ route('admin.reportes.general') }}"> General</a>
                    <a href="{{ route('admin.reportes.vivienda') }}"> Por Vivienda</a>
                    <a href="{{ route('admin.reportes.morosidad') }}"> Morosidad</a>
                </div>
            </li>
            <li><a href="{{ route('admin.tarifas.edit') }}">Tarifas</a></li>
            <li class="logout-item">
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">@csrf</form>
                <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <i class="fas fa-sign-out-alt me-1"></i> Salir
                </a>
            </li>
        </ul>
    </nav>
    <main class="main-wrapper">
        <div class="main-card">
            @yield('content')
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>