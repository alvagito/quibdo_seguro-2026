<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Iniciar Sesión - Quibdó Seguro</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="auth-body">
    <div class="auth-container">
        <!-- Panel Izquierdo - Información -->
        <div class="auth-panel auth-panel-left">
            <div class="auth-brand">
                <i class="fas fa-shield-alt"></i>
                <h1>Quibdó Seguro</h1>
            </div>
            <div class="auth-info">
                <h2>Bienvenido de nuevo</h2>
                <p>Inicia sesión para acceder a tu cuenta y continuar reportando incidentes en tu comunidad.</p>
                <div class="auth-features">
                    <div class="feature-item">
                        <i class="fas fa-map-marked-alt"></i>
                        <span>Reporta incidentes en tiempo real</span>
                    </div>
                    <div class="feature-item">
                        <i class="fas fa-gift"></i>
                        <span>Gana puntos y recompensas</span>
                    </div>
                    <div class="feature-item">
                        <i class="fas fa-users"></i>
                        <span>Contribuye a tu comunidad</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Panel Derecho - Formulario -->
        <div class="auth-panel auth-panel-right">
            <div class="auth-form-container">
                <div class="auth-form-header">
                    <h2>Iniciar Sesión</h2>
                    <p>Ingresa tus credenciales para continuar</p>
                </div>

                <!-- Mostrar errores -->
                @if ($errors->any())
                    <div class="auth-alert auth-alert-error">
                        <i class="fas fa-exclamation-circle"></i>
                        <div>
                            @foreach ($errors->all() as $error)
                                <p>{{ $error }}</p>
                            @endforeach
                        </div>
                    </div>
                @endif

                @if(session('success'))
                    <div class="auth-alert auth-alert-success">
                        <i class="fas fa-check-circle"></i>
                        <p>{{ session('success') }}</p>
                    </div>
                @endif

                <!-- Formulario de login -->
                <form action="{{ url('/login') }}" method="POST" class="auth-form">
                    @csrf
                    
                    <div class="auth-form-group">
                        <label for="email">
                            <i class="fas fa-envelope"></i>
                            Correo Electrónico
                        </label>
                        <input 
                            type="email" 
                            name="email" 
                            id="email" 
                            value="{{ old('email') }}" 
                            placeholder="tu@email.com"
                            required
                            autofocus
                        >
                    </div>

                    <div class="auth-form-group">
                        <label for="password">
                            <i class="fas fa-lock"></i>
                            Contraseña
                        </label>
                        <div class="password-input-wrapper">
                            <input 
                                type="password" 
                                name="password" 
                                id="password" 
                                placeholder="••••••••"
                                required
                            >
                            <button type="button" class="toggle-password" onclick="togglePassword()">
                                <i class="fas fa-eye" id="toggleIcon"></i>
                            </button>
                        </div>
                    </div>

                    <button type="submit" class="auth-btn auth-btn-primary">
                        <i class="fas fa-sign-in-alt"></i>
                        Iniciar Sesión
                    </button>
                </form>

                <div class="auth-divider">
                    <span>¿No tienes cuenta?</span>
                </div>

                <a href="{{ url('/register') }}" class="auth-btn auth-btn-secondary">
                    <i class="fas fa-user-plus"></i>
                    Crear una cuenta
                </a>

                <div class="auth-footer">
                    <a href="{{ url('/') }}" class="auth-link">
                        <i class="fas fa-arrow-left"></i>
                        Volver al inicio
                    </a>
                </div>
            </div>
        </div>
    </div>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        .auth-body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .auth-container {
            display: flex;
            max-width: 1100px;
            width: 100%;
            background: white;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            min-height: 600px;
        }

        .auth-panel {
            flex: 1;
            padding: 60px;
        }

        .auth-panel-left {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .auth-brand {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 40px;
        }

        .auth-brand i {
            font-size: 3rem;
        }

        .auth-brand h1 {
            font-size: 2rem;
            font-weight: 700;
        }

        .auth-info h2 {
            font-size: 2.5rem;
            margin-bottom: 20px;
            font-weight: 700;
        }

        .auth-info p {
            font-size: 1.1rem;
            line-height: 1.6;
            opacity: 0.95;
            margin-bottom: 40px;
        }

        .auth-features {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .feature-item {
            display: flex;
            align-items: center;
            gap: 15px;
            font-size: 1rem;
        }

        .feature-item i {
            font-size: 1.5rem;
            opacity: 0.9;
        }

        .auth-panel-right {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .auth-form-container {
            width: 100%;
            max-width: 400px;
        }

        .auth-form-header {
            margin-bottom: 30px;
        }

        .auth-form-header h2 {
            font-size: 2rem;
            color: #2d3748;
            margin-bottom: 10px;
        }

        .auth-form-header p {
            color: #718096;
            font-size: 1rem;
        }

        .auth-alert {
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 20px;
            display: flex;
            align-items: flex-start;
            gap: 12px;
        }

        .auth-alert i {
            font-size: 1.2rem;
            margin-top: 2px;
        }

        .auth-alert-error {
            background: #fee;
            color: #c53030;
            border: 1px solid #fc8181;
        }

        .auth-alert-success {
            background: #efe;
            color: #2f855a;
            border: 1px solid #68d391;
        }

        .auth-alert p {
            margin: 0;
            line-height: 1.5;
        }

        .auth-form {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .auth-form-group {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .auth-form-group label {
            font-weight: 600;
            color: #2d3748;
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 0.95rem;
        }

        .auth-form-group label i {
            color: #667eea;
        }

        .auth-form-group input {
            padding: 14px 16px;
            border: 2px solid #e2e8f0;
            border-radius: 10px;
            font-size: 1rem;
            transition: all 0.3s;
            font-family: inherit;
        }

        .auth-form-group input:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .password-input-wrapper {
            position: relative;
        }

        .password-input-wrapper input {
            width: 100%;
            padding-right: 50px;
        }

        .toggle-password {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: #718096;
            cursor: pointer;
            padding: 8px;
            font-size: 1.1rem;
            transition: color 0.3s;
        }

        .toggle-password:hover {
            color: #667eea;
        }

        .auth-btn {
            padding: 14px 24px;
            border-radius: 10px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            text-decoration: none;
            border: none;
            font-family: inherit;
        }

        .auth-btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            margin-top: 10px;
        }

        .auth-btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(102, 126, 234, 0.3);
        }

        .auth-btn-secondary {
            background: white;
            color: #667eea;
            border: 2px solid #667eea;
        }

        .auth-btn-secondary:hover {
            background: #f7fafc;
        }

        .auth-divider {
            text-align: center;
            margin: 25px 0;
            color: #718096;
            font-size: 0.9rem;
        }

        .auth-footer {
            margin-top: 30px;
            text-align: center;
        }

        .auth-link {
            color: #667eea;
            text-decoration: none;
            font-size: 0.95rem;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: color 0.3s;
        }

        .auth-link:hover {
            color: #764ba2;
        }

        @media (max-width: 968px) {
            .auth-panel-left {
                display: none;
            }

            .auth-panel {
                padding: 40px 30px;
            }
        }

        @media (max-width: 480px) {
            .auth-panel {
                padding: 30px 20px;
            }

            .auth-form-header h2 {
                font-size: 1.5rem;
            }
        }
    </style>

    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const toggleIcon = document.getElementById('toggleIcon');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleIcon.classList.remove('fa-eye');
                toggleIcon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                toggleIcon.classList.remove('fa-eye-slash');
                toggleIcon.classList.add('fa-eye');
            }
        }
    </script>
</body>
</html>
