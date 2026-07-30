<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Acceso SIDUMSS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #f4f7f6; display: flex; align-items: center; height: 100vh; }
        .login-card { width: 100%; max-width: 400px; padding: 30px; margin: auto; }
    </style>
</head>
<body>
    <div class="login-card card shadow">
        <h2 class="text-center mb-4">SIDUMSS</h2>
        <form action="{{ route('login.post') }}" method="POST">
         @csrf
            <div class="mb-3">
                <label>Correo Electrónico</label>
                <input type="email" name="email" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Contraseña</label>
                <input type="password" name="password" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary w-100 py-2">Ingresar al Sistema</button>
        </form>
        
        @if($errors->any())
            <div class="alert alert-danger mt-3 small">{{ $errors->first() }}</div>
        @endif
    </div>
</body>
</html>