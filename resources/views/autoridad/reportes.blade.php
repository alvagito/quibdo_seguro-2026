<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reportes Recibidos - Quibdó Seguro</title>
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
        <h2>Reportes Recibidos</h2>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <table class="styled-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Descripción</th>
                    <th>Usuario</th>
                    <th>Fecha</th>
                    <th>Estado</th>
                    <th>Acción</th>
                </tr>
            </thead>
            <tbody>
                @foreach($reportes as $rep)
                    <tr>
                        <td>{{ $rep->_id }}</td>
                        <td>{{ Str::limit($rep->descripcion, 50) }}</td>
                        <td>{{ $rep->usuario->nombre ?? 'N/A' }}</td>
                        <td>{{ \Carbon\Carbon::parse($rep->fecha_hora_reporte)->format('d/m/Y H:i') }}</td>
                        <td>
                            @switch($rep->id_estado)
                                @case(1)
                                    <span class="status-badge status-pendiente">Pendiente</span>
                                    @break
                                @case(2)
                                    <span class="status-badge status-validado">Validado</span>
                                    @break
                                @case(3)
                                    <span class="status-badge status-rechazado">Rechazado</span>
                                    @break
                                @default
                                    <span class="status-badge">{{ $rep->id_estado }}</span>
                            @endswitch
                        </td>
                        <td>
                            <a href="{{ url('/autoridad/validar_accion/'.$rep->_id) }}" class="btn btn-secundario">Revisar</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</main>

<footer>
    <p>© {{ date('Y') }} Quibdó Seguro - Todos los derechos reservados.</p>
</footer>
</body>
</html>
