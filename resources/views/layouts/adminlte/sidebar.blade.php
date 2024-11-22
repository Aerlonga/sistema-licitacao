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
                        <p>Licitações</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('listarLicitacoes') }}" class="nav-link">
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
                <!-- Google Translate Widget -->
                <li class="nav-item">
                    <div id="google_translate_element"></div>
                </li>
            </ul>
        </nav>
    </div>
</aside>

<!-- Adicione o script do Google Translate Widget -->
{{-- <script type="text/javascript">
    function googleTranslateElementInit() {
        new google.translate.TranslateElement({
            pageLanguage: 'pt',
            includedLanguages: 'en,es,fr,de,pt', // Modifique os idiomas conforme necessário
            layout: google.translate.TranslateElement.InlineLayout.SIMPLE
        }, 'google_translate_element');
    }
</script>
<script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit">
</script> --}}
