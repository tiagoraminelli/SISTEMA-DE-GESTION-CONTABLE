<link rel="stylesheet" href="{{ asset('css/welcome.index.css') }}">

<div class="welcome-card">
    {{-- <img src="{{ asset('utils/logo.jpg') }}" alt="Logo"> --}}
    <img src="{{ asset('utils/logo-2.png') }}" alt="Logo2">
    <h1>(NOMBRE) - CONTABLE</h1>
    <p>Gestiona clientes, asientos y reportes de manera eficiente y profesional.</p>

    <div class="buttons">
        @if (Route::has('login'))
            @auth
                <a href="{{ url('/dashboard') }}" class="btn btn-dashboard">Ir al Panel</a>
            @else
                <a href="{{ route('login') }}" class="btn btn-login">Login</a>
                @if (Route::has('register'))
                    <a href="{{ route('register') }}" class="btn btn-register">Registrarse</a>
                @endif
            @endauth
        @endif
    </div>

    <footer> &copy; {{ date('Y') }} RAMNELL CONTABLE. Todos los derechos reservados.</footer>
</div>
