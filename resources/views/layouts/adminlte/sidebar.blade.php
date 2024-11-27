<!-- Menu Lateral (Sidebar) -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Logo do painel -->
    <a href="#" class="brand-link">
        <span class="brand-text font-weight-light">Controle Licitações</span>
    </a>

    <!-- Menu -->
    <div class="sidebar">
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <li class="nav-item">
                    <a href="{{ route('inicio') }}" class="nav-link">
                        <i class="nav-icon fas fa-home"></i>
                        <p>Início</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('EquipeSalva') }}" class="nav-link">
                        <i class="nav-icon fas fa-user"></i>
                        <p>Gerar Equipe</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('visualizarLicitacoes') }}" class="nav-link">
                        <i class="nav-icon fas fa-briefcase"></i>
                        <p>Contratações</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('checklist') }}" class="nav-link">
                        <i class="nav-icon fas fa-file-alt"></i>
                        <p>Checklist</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('contato') }}" class="nav-link">
                        <i class="nav-icon fas fa-envelope"></i>
                        <p>Contato</p>
                    </a>
                </li>
                @if (Auth::check() && Auth::user()->role === 'admin')
                    <li class="nav-item">
                        <a href="{{ route('configuracoes') }}" class="nav-link">
                            <i class="nav-icon fas fa-cog"></i>
                            <p>Configurações</p>
                        </a>
                    </li>
                @endif
            </ul>
        </nav>
    </div>
</aside>
