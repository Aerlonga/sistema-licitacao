<nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Botão para esconder/mostrar a sidebar -->
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>
    </ul>
    <div class="container">
        <a href="{{ route('EquipeSalva') }}" class="navbar-brand">
            <span class="brand-text font-weight-light"></span>
        </a>
    </div>

    <!-- Área de login e sair no canto superior direito -->
    <ul class="navbar-nav ml-auto">
        <!-- Exibir apenas se o usuário não estiver autenticado -->
        @guest
            <li class="nav-item">
                <a href="{{ route('login') }}" class="nav-link">
                    <i class="fas fa-sign-in-alt"></i> Login
                </a>
            </li>
        @endguest

        <!-- Exibir apenas se o usuário estiver autenticado -->
        @auth
            {{-- <button onclick="switchToEnglish()">English</button>
            <button onclick="switchToPortuguese()">Português</button> --}}
            <li class="nav-item">
                <span class="nav-link">Bem-vindo, {{ Auth::user()->name }}</span>
            </li>
            <li class="nav-item">
                <a href="{{ route('logout') }}" class="nav-link"
                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <i class="fas fa-sign-out-alt"></i> Sair
                </a>
                <!-- Formulário de Logout (necessário para suportar logout seguro) -->
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
            </li>
        @endauth
    </ul>
</nav>
