<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión de Reportes - Admin</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="{{ asset('assets/css/styles.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/pages.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
@include('components.header')

<main style="background: #f5f7fa; min-height: 100vh; padding: 2rem 0;">
    <div class="page-hero">
        <h1><i class="fas fa-clipboard-list"></i> Gestión de Reportes</h1>
        <p>Administra todos los reportes de incidentes</p>
    </div>

    <div class="modern-container">
        @if(session('success'))
            <div class="alert-modern alert-modern-success">
                <i class="fas fa-check-circle"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        <!-- Filtros -->
        <div class="modern-card" style="margin-bottom: 2rem;">
            <form method="GET" action="{{ url('/admin/reportes') }}" style="display: flex; gap: 1rem; flex-wrap: wrap; align-items: end;">
                <div class="modern-form-group" style="flex: 1; min-width: 250px; margin-bottom: 0;">
                    <label><i class="fas fa-search"></i> Buscar</label>
                    <input type="text" name="buscar" placeholder="Descripción o dirección..." value="{{ request('buscar') }}">
                </div>
                <div class="modern-form-group" style="min-width: 150px; margin-bottom: 0;">
                    <label><i class="fas fa-list"></i> Tipo</label>
                    <select name="tipo" onchange="this.form.submit()">
                        <option value="">Todos</option>
                        <option value="1" {{ request('tipo') == '1' ? 'selected' : '' }}>Robo</option>
                        <option value="2" {{ request('tipo') == '2' ? 'selected' : '' }}>Accidente</option>
                        <option value="3" {{ request('tipo') == '3' ? 'selected' : '' }}>Violencia</option>
                        <option value="4" {{ request('tipo') == '4' ? 'selected' : '' }}>Otro</option>
                    </select>
                </div>

                <button type="submit" class="btn-modern"><i class="fas fa-search"></i> Buscar</button>
                @if(request()->hasAny(['buscar', 'tipo']))
                    <a href="{{ url('/admin/reportes') }}" class="btn-modern" style="background: #e74c3c;">
                        <i class="fas fa-times"></i> Limpiar
                    </a>
                @endif
            </form>
        </div>

        <!-- Tabla de Reportes -->
        <div class="modern-card">
            <div class="modern-card-header">
                <h2><i class="fas fa-list"></i> Lista de Reportes ({{ $reportes->total() }})</h2>
            </div>

            <table class="modern-table">
                <thead>
                    <tr>
                        <th><i class="fas fa-user"></i> Usuario</th>
                        <th><i class="fas fa-align-left"></i> Descripción</th>
                        <th><i class="fas fa-map-marker-alt"></i> Ubicación</th>
                        <th><i class="fas fa-tag"></i> Tipo</th>

                        <th><i class="fas fa-calendar"></i> Fecha</th>
                        <th><i class="fas fa-cog"></i> Acción</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($reportes as $r)
                        <tr>
                            <td><strong>{{ $r->usuario->nombre ?? 'N/A' }}</strong></td>
                            <td>{{ Str::limit($r->descripcion, 50) }}</td>
                            <td style="font-size: 0.85rem;">{{ Str::limit($r->direccion_aproximada, 30) }}</td>
                            <td>
                                <span class="badge-modern 
                                    @if($r->id_tipo_incidente == 1) badge-danger
                                    @elseif($r->id_tipo_incidente == 2) badge-warning
                                    @elseif($r->id_tipo_incidente == 3) badge-info
                                    @else badge-secondary
                                    @endif">
                                    @switch($r->id_tipo_incidente)
                                        @case(1) Robo @break
                                        @case(2) Accidente @break
                                        @case(3) Violencia @break
                                        @default Otro
                                    @endswitch
                                </span>
                            </td>

                            <td>{{ $r->created_at->format('d/m/Y') }}</td>
                            <td>
                                <form action="{{ url('/admin/reportes/'.$r->_id) }}" method="POST" style="display:inline;" onsubmit="return confirm('¿Eliminar este reporte?');">
                                    @csrf @method('DELETE')
                                    <button type="submit" style="padding: 0.5rem 1rem; background: #e74c3c; color: white; border: none; border-radius: 6px; cursor: pointer; font-size: 0.9rem;">
                                        <i class="fas fa-trash"></i> Eliminar
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" style="text-align: center; padding: 2rem; color: #718096;">
                                <i class="fas fa-inbox" style="font-size: 2rem; margin-bottom: 1rem; display: block;"></i>
                                No se encontraron reportes
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            @if($reportes->hasPages())
                <div style="padding: 1.5rem; border-top: 1px solid #e2e8f0;">
                    {{ $reportes->links() }}
                </div>
            @endif
        </div>
    </div>
</main>

<footer>
    <p>© {{ date('Y') }} Quibdó Seguro - Panel de Administración</p>
</footer>
</body>
</html>
