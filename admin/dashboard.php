<?php
// ============================
// ADICIONAR MENU NO ADMIN DO WORDPRESS
// ============================
function bia_menu() {
    add_menu_page(
        'BIA - Blog Infinito Automático',
        'BIA - Blog Infinito Automático',
        'manage_options',
        'bia-blog-infinito-automatico',
        'bia_render_dashboard',
        'dashicons-admin-generic',
        25
    );
}
add_action('admin_menu', 'bia_menu');

// ============================
// RENDERIZAR O DASHBOARD DO PLUGIN
// ============================
function bia_render_dashboard() {
    $tab = isset($_GET['tab']) ? sanitize_text_field($_GET['tab']) : 'minha_conta';

    echo '<div class="wrap">';
    echo '<h1>BIA - Blog Infinito Automático</h1>';
    echo '<h2 class="nav-tab-wrapper">';
    echo '<a href="?page=bia-blog-infinito-automatico&tab=minha_conta" class="nav-tab ' . ($tab === 'minha_conta' ? 'nav-tab-active' : '') . '">Minha Conta</a>';
    echo '<a href="?page=bia-blog-infinito-automatico&tab=gerar_ideias" class="nav-tab ' . ($tab === 'gerar_ideias' ? 'nav-tab-active' : '') . '">Gerar Ideias</a>';
    echo '<a href="?page=bia-blog-infinito-automatico&tab=produzir_conteudos" class="nav-tab ' . ($tab === 'produzir_conteudos' ? 'nav-tab-active' : '') . '">Produzir Conteúdos</a>';
    echo '<a href="?page=bia-blog-infinito-automatico&tab=agendamento" class="nav-tab ' . ($tab === 'agendamento' ? 'nav-tab-active' : '') . '">Agendar Posts</a>';
    echo '<a href="?page=bia-blog-infinito-automatico&tab=calendario" class="nav-tab ' . ($tab === 'calendario' ? 'nav-tab-active' : '') . '">Calendário</a>';
    echo '<a href="?page=bia-blog-infinito-automatico&tab=historico" class="nav-tab ' . ($tab === 'historico' ? 'nav-tab-active' : '') . '">Histórico</a>';
    echo '<a href="?page=bia-blog-infinito-automatico&tab=excluidos" class="nav-tab ' . ($tab === 'excluidos' ? 'nav-tab-active' : '') . '">Excluídos</a>';
    echo '<a href="?page=bia-blog-infinito-automatico&tab=loja_da_bia" class="nav-tab ' . ($tab === 'loja_da_bia' ? 'nav-tab-active' : '') . '">Loja da BIA</a>';
    echo '<a href="?page=bia-blog-infinito-automatico&tab=seja_afiliado" class="nav-tab ' . ($tab === 'seja_afiliado' ? 'nav-tab-active' : '') . '">Seja Afiliado</a>';
    echo '</h2>';

    switch ($tab) {
        case 'minha_conta':
            bia_minha_conta();
            break;
        case 'gerar_ideias':
            bia_gerar_ideias();
            break;
        case 'produzir_conteudos':
            bia_produzir_conteudos();
            break;
        case 'agendamento':
            bia_agendamento_rascunhos();
            break;
        case 'calendario':
            bia_calendario();
            break;
        case 'historico':
            bia_historico();
            break;
        case 'excluidos':
            bia_excluidos();
            break;
            case 'loja_da_bia':
    bia_loja_da_bia();
    break;
        case 'seja_afiliado':
            bia_seja_afiliado();
            break;
        default:
            bia_minha_conta();
            break;
    }

    echo '</div>';
}

// ============================
// ENQUEUE PARA PRODUZIR CONTEÚDOS
// ============================
function bia_enqueue_produzir_conteudos_assets($hook) {
    if ($hook !== 'toplevel_page_bia-blog-infinito-automatico') {
        return;
    }

    if (!isset($_GET['tab']) || $_GET['tab'] !== 'produzir_conteudos') {
        return;
    }

    // FullCalendar
    wp_enqueue_style('fullcalendar-core', 'https://cdn.jsdelivr.net/npm/@fullcalendar/core@6.1.8/main.min.css');
    wp_enqueue_script('fullcalendar-core', 'https://cdn.jsdelivr.net/npm/@fullcalendar/core@6.1.8/main.min.js', array('jquery'), null, true);
    wp_enqueue_script('fullcalendar-daygrid', 'https://cdn.jsdelivr.net/npm/@fullcalendar/daygrid@6.1.8/main.min.js', array('fullcalendar-core'), null, true);
    wp_enqueue_script('fullcalendar-timegrid', 'https://cdn.jsdelivr.net/npm/@fullcalendar/timegrid@6.1.8/main.min.js', array('fullcalendar-core'), null, true);
    wp_enqueue_script('fullcalendar-interaction', 'https://cdn.jsdelivr.net/npm/@fullcalendar/interaction@6.1.8/main.min.js', array('fullcalendar-core'), null, true);

    // Custom CSS & JS
    wp_enqueue_style('bia-producao-css', BIA_URL . 'assets/css/produzir-conteudos.css', array(), null);
    wp_enqueue_script('bia-producao-js', BIA_URL . 'assets/js/produzir-conteudos.js', array('jquery'), null, true);
}
add_action('admin_enqueue_scripts', 'bia_enqueue_produzir_conteudos_assets');