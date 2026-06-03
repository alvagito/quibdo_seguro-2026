<header>
    <div class="logo">
        <h1><a href="{{ url('/') }}">Quibdó Seguro</a></h1>
    </div>
    <nav>
        <ul>
            @auth
                @php
                    $userRole = Auth::user()->rol;
                @endphp

                @if($userRole === 'admin')
                    <!-- Menú para Administrador -->
                    <li><a href="{{ url('/admin') }}"><i class="fas fa-tachometer-alt"></i> Panel Admin</a></li>
                    <li><a href="{{ url('/admin/usuarios') }}"><i class="fas fa-users"></i> Usuarios</a></li>
                    <li><a href="{{ url('/admin/reportes') }}"><i class="fas fa-file-alt"></i> Reportes</a></li>
                    <li><a href="{{ url('/admin/comercios') }}"><i class="fas fa-store"></i> Comercios</a></li>
                    <li><a href="{{ url('/admin/ofertas') }}"><i class="fas fa-tags"></i> Ofertas</a></li>
                    <li><a href="{{ url('/admin/recompensas') }}"><i class="fas fa-gift"></i> Recompensas</a></li>



                @elseif($userRole === 'comercio')
                    <!-- Menú para Comercio -->
                    <li><a href="{{ url('/comercio') }}"><i class="fas fa-store"></i> Mi Comercio</a></li>
                    <li><a href="{{ url('/comercio/ofertas') }}"><i class="fas fa-tags"></i> Mis Ofertas</a></li>
                    <li><a href="{{ url('/comercio/estadisticas') }}"><i class="fas fa-chart-bar"></i> Estadísticas</a></li>

                @else
                    <!-- Menú para Usuario Normal -->
                    <li><a href="{{ url('/dashboard') }}"><i class="fas fa-home"></i> Inicio</a></li>
                    <li><a href="{{ url('/perfil') }}"><i class="fas fa-user"></i> Perfil</a></li>
                    <li><a href="{{ url('/mapa') }}"><i class="fas fa-map"></i> Mapa</a></li>
                    <li><a href="{{ url('/reportar') }}"><i class="fas fa-exclamation-triangle"></i> Reportar</a></li>
                    <li><a href="{{ url('/recompensas') }}"><i class="fas fa-gift"></i> Recompensas</a></li>
                    <li><a href="{{ url('/mis-canjes') }}"><i class="fas fa-ticket-alt"></i> Mis Canjes</a></li>
                @endif

                <!-- Cerrar Sesión (para todos los roles) -->
                <li>
                    <form action="{{ url('/logout') }}" method="POST" style="display:inline;">
                        @csrf
                        <button type="submit" class="btn btn-header">
                            <i class="fas fa-sign-out-alt"></i> Cerrar Sesión
                        </button>
                    </form>
                </li>
            @else
                <li><a href="{{ url('/login') }}"><i class="fas fa-sign-in-alt"></i> Iniciar Sesión</a></li>
                <li><a href="{{ url('/register') }}"><i class="fas fa-user-plus"></i> Registrarse</a></li>
            @endauth
        </ul>
    </nav>
</header>
