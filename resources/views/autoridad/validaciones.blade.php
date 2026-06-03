<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Validaciones Pendientes - Quibdó Seguro</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="{{ asset('assets/css/styles.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/theme.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
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
        <h2>Validaciones Pendientes</h2>

        <table class="styled-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Descripción</th>
                    <th>Usuario</th>
                    <th>Fecha</th>
                    <th>Acción</th>
                </tr>
            </thead>
            <tbody>
                @forelse($reportes as $rep)
                    <tr>
                        <td>#{{ $rep->_id }}</td>
                        <td>{{ Str::limit($rep->descripcion, 70) }}</td>
                        <td>{{ $rep->usuario->nombre ?? 'N/A' }}</td>
                        <td>{{ \Carbon\Carbon::parse($rep->fecha_hora_reporte)->format('d/m/Y H:i') }}</td>
                        <td><a href="{{ url('/autoridad/validar_accion/'.$rep->_id) }}" class="btn btn-acento">Validar</a></td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" style="text-align: center;">No hay reportes pendientes de validación.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</main>

<footer>
    <p>© {{ date('Y') }} Quibdó Seguro - Todos los derechos reservados.</p>
</footer>
</body>
</html>
