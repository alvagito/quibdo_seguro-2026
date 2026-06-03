<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quibdó Seguro</title>
    <link rel="stylesheet" href="{{ asset('assets/css/styles.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/theme.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    
    {{-- HEADER --}}
    @include('components.header')

    {{-- CONTENIDO PRINCIPAL --}}
    <main>
        @yield('content')
    </main>

    {{-- FOOTER --}}
    <footer>
        <p>© {{ date('Y') }} Quibdó Seguro - Todos los derechos reservados.</p>
    </footer>

    <script src="{{ asset('assets/js/app.js') }}"></script>
    
</body>
</html>
