<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Validar Reporte - Quibdó Seguro</title>
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
    <div class="form-box" style="max-width: 600px;">
        <h2>Validar Reporte #{{ $reporte->_id }}</h2>

        <div class="reporte-detalle">
            <p><strong>Descripción:</strong> {{ $reporte->descripcion }}</p>
            <p><strong>Dirección:</strong> {{ $reporte->direccion_aproximada }}</p>
            <p><strong>Fecha:</strong> {{ \Carbon\Carbon::parse($reporte->fecha_hora_reporte)->format('d/m/Y H:i') }}</p>
            <p><strong>Reportado por:</strong> {{ $reporte->usuario->nombre ?? 'N/A' }}</p>
        </div>

        <form action="{{ url('/autoridad/validar_accion/'.$reporte->_id) }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="comentarios">Comentarios de la Autoridad</label>
                <textarea name="comentarios" id="comentarios" rows="3">{{ $reporte->comentarios_autoridad }}</textarea>
            </div>
            <div class="form-group">
                <label for="estado">Estado</label>
                <select name="estado" id="estado">
                    <option value="1" @if($reporte->id_estado == 1) selected @endif>Pendiente</option>
                    <option value="2" @if($reporte->id_estado == 2) selected @endif>Validado</option>
                    <option value="3" @if($reporte->id_estado == 3) selected @endif>Rechazado</option>
                </select>
            </div>
            <button type="submit" class="btn btn-acento">Guardar Validación</button>
        </form>
    </div>
</main>

<footer>
    <p>© {{ date('Y') }} Quibdó Seguro - Todos los derechos reservados.</p>
</footer>
</body>
</html>
