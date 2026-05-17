<nav class="top-nav">
    <a class="brand-dot" href="{{ route('home') }}" aria-label="Mokkao inicio">
        <img src="{{ asset('appearance/shared/img/148048b4f6a61eeffeacd39f0a157776450cf6c7.png') }}" alt="Mokkao">
    </a>
    <div class="nav-pill">
        <a href="{{ route('menu.index') }}">Shop</a>
        <a href="{{ route('home') }}#about">About</a>
        <a href="{{ route('menu.index') }}">Carta</a>
    </div>
    <a class="contact-pill" href="#contacto">Contacto</a>
    @auth
        <details class="account-menu">
            <summary class="icon-pill" aria-label="Cuenta">
                <svg viewBox="0 0 35 35"><path d="M27.7083 30.625V27.7083C27.7083 26.1612 27.0937 24.6775 25.9998 23.5835C24.9058 22.4896 23.4221 21.875 21.875 21.875H13.125C11.5779 21.875 10.0942 22.4896 9.00021 23.5835C7.90625 24.6775 7.29167 26.1612 7.29167 27.7083V30.625M23.3333 10.2083C23.3333 13.43 20.7217 16.0417 17.5 16.0417C14.2783 16.0417 11.6667 13.43 11.6667 10.2083C11.6667 6.98667 14.2783 4.375 17.5 4.375C20.7217 4.375 23.3333 6.98667 23.3333 10.2083Z"/></svg>
            </summary>
            <div class="account-dropdown">
                <a href="{{ route('profile.show') }}">Perfil</a>
                <a href="{{ route('orders.index') }}">Mis pedidos</a>
                @if (auth()->user()->rol === 'administrador')
                    <a href="{{ route('admin.dashboard') }}">Administración</a>
                @endif
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit">Cerrar sesión</button>
                </form>
            </div>
        </details>
    @else
        <a class="icon-pill" href="{{ route('login') }}" aria-label="Cuenta">
            <svg viewBox="0 0 35 35"><path d="M27.7083 30.625V27.7083C27.7083 26.1612 27.0937 24.6775 25.9998 23.5835C24.9058 22.4896 23.4221 21.875 21.875 21.875H13.125C11.5779 21.875 10.0942 22.4896 9.00021 23.5835C7.90625 24.6775 7.29167 26.1612 7.29167 27.7083V30.625M23.3333 10.2083C23.3333 13.43 20.7217 16.0417 17.5 16.0417C14.2783 16.0417 11.6667 13.43 11.6667 10.2083C11.6667 6.98667 14.2783 4.375 17.5 4.375C20.7217 4.375 23.3333 6.98667 23.3333 10.2083Z"/></svg>
        </a>
    @endauth
    <a class="icon-pill" href="{{ route('cart.index') }}" aria-label="Carrito">
        <svg viewBox="0 0 35 35"><path d="M2.98958 2.98958H5.90625L9.78542 21.1021C9.92772 21.7654 10.2968 22.3584 10.8292 22.779C11.3615 23.1995 12.0238 23.4213 12.7021 23.4062H26.9646C27.6284 23.4052 28.272 23.1777 28.789 22.7614C29.306 22.3451 29.6656 21.7649 29.8083 21.1167L32.2146 10.2813H7.46667M13.125 30.625C13.125 31.4304 12.4721 32.0833 11.6667 32.0833C10.8613 32.0833 10.2083 31.4304 10.2083 30.625C10.2083 29.8196 10.8613 29.1667 11.6667 29.1667C12.4721 29.1667 13.125 29.8196 13.125 30.625ZM29.1667 30.625C29.1667 31.4304 28.5138 32.0833 27.7083 32.0833C26.9029 32.0833 26.25 31.4304 26.25 30.625C26.25 29.8196 26.9029 29.1667 27.7083 29.1667C28.5138 29.1667 29.1667 29.8196 29.1667 30.625Z"/></svg>
        <span>{{ collect(session('cart', []))->sum('cantidad') }}</span>
    </a>
</nav>
