<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel de Autoridad - Quibdó Seguro</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="{{ asset('assets/css/styles.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/theme.css') }}">
</head>
<body>
<header>
    <div class="logo">
        <h1><a href="{{ url('/') }}">Quibdó Seguro</a></h1>
    </div>
    <nav>
        <ul>
            <li><a href="{{ url('/autoridad') }}">Panel</a></li>
            <li><a href="{{ url('/autoridad/reportes') }}">Reportes</a></li>
            <li><a href="{{ url('/autoridad/validaciones') }}">Validaciones</a></li>
            <li>
                <form action="{{ url('/logout') }}" method="POST" style="display:inline;">
                    @csrf
                    <button type="submit" class="btn btn-header">Cerrar Sesión</button>
                </form>
            </li>
        </ul>
    </nav>
</header>

<main>
    <div class="recompensas-container">
        <h2>Panel de Autoridad</h2>
        <p>Bienvenido, aquí puedes gestionar y validar los reportes de incidentes enviados por los ciudadanos.</p>

        <div class="dashboard-actions">
            <a href="{{ url('/autoridad/reportes') }}" class="btn btn-secundario">Ver Todos los Reportes</a>
            <a href="{{ url('/autoridad/validaciones') }}" class="btn btn-acento">Validaciones Pendientes</a>
        </div>
    </div>
</main>

<footer>
    <p>© {{ date('Y') }} Quibdó Seguro - Todos los derechos reservados.</p>
</footer>
</body>
</html>
