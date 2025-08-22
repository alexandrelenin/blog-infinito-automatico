<?php
/*
Plugin Name: BIA - Blog Infinito Automático
Plugin URI: https://bloginfinitoautomatico.com.br
Description: Gere ideias, produza conteúdos e publique automaticamente no seu blog usando IA!
Version: 1.2.0
Author: Murilo Parrillo
Author URI: https://bloginfinitoautomatico.com.br
License: GPL v2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Text Domain: bia
Domain Path: /languages
Requires at least: 5.0
Tested up to: 6.8
Requires PHP: 7.4
Network: false
*/

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

define('BIA_PATH', plugin_dir_path(__FILE__));
define('BIA_URL', plugin_dir_url(__FILE__));

// ============================
// INCLUDES PRINCIPAIS
// ============================
require_once BIA_PATH . 'includes/bia-licenca-verificacao.php';

// ============================
// CSS MODERNIZADO
// ============================
function bia_enqueue_modern_styles() {
    $tab = isset($_GET['tab']) ? sanitize_text_field($_GET['tab']) : '';
    $page = isset($_GET['page']) ? sanitize_text_field($_GET['page']) : '';

    if ($page === 'bia-blog-infinito-automatico') {
        // CSS da Loja
        if ($tab === 'loja_da_bia') {
            wp_enqueue_style('bia-loja-style', BIA_URL . 'assets/css/loja-da-bia.css', array(), time());
        }

        // CSS da Produção de Conteúdos (mesmo sem tab definido)
        if (empty($tab) || $tab === 'produzir-conteudos') {
            wp_enqueue_style('bia-produzir-conteudos-style', BIA_URL . 'assets/css/produzir-conteudos.css', array(), time());
        }

        // CSS do Agendamento
        if ($tab === 'agendamento-rascunhos') {
            wp_enqueue_style('bia-agendamento-style', BIA_URL . 'assets/css/agendamento.css', array(), time());
        }
    }
}
add_action('admin_enqueue_scripts', 'bia_enqueue_modern_styles');

// Incluir PRIMEIRO todas as abas (funções)
require_once BIA_PATH . 'admin/tabs/minha-conta.php';

if (!defined('BIA_PLUGIN_BLOQUEADO')) {
    require_once BIA_PATH . 'admin/tabs/gerar-ideias.php';
    require_once BIA_PATH . 'admin/tabs/produzir-conteudos.php';
    require_once BIA_PATH . 'admin/tabs/produzir-conteudos-funcionalidades.php';
    require_once BIA_PATH . 'admin/tabs/gerar-imagem.php';
    require_once BIA_PATH . 'admin/tabs/agendamento-rascunhos.php';
    require_once BIA_PATH . 'admin/tabs/calendario.php';
    require_once BIA_PATH . 'admin/tabs/historico.php';
    require_once BIA_PATH . 'admin/tabs/excluidos.php';
    require_once BIA_PATH . 'admin/tabs/loja-da-bia.php';
    require_once BIA_PATH . 'admin/tabs/seja-afiliado.php';
    require_once BIA_PATH . 'admin/tabs/changelog.php';
}

// Incluir dashboard POR ÚLTIMO (usa as funções das abas)
require_once BIA_PATH . 'admin/dashboard.php';

// ============================
// INCLUDES OPCIONAIS
// ============================
$prompt_path = BIA_PATH . 'admin/tabs/prompt.php';
if (file_exists($prompt_path)) {
    require_once $prompt_path;
}

$mass_content_path = BIA_PATH . 'admin/tabs/gerar-conteudos-em-massa.php';
if (file_exists($mass_content_path)) {
    require_once $mass_content_path;
}

// ============================
// ATIVAÇÃO E DESATIVAÇÃO
// ============================
register_activation_hook(__FILE__, 'bia_plugin_ativar');
register_deactivation_hook(__FILE__, 'bia_plugin_desativar');

function bia_plugin_ativar() {
    delete_option('bia_gpt_dalle_key');
    delete_option('bia_nome_completo');
    delete_option('bia_email_compra');
    delete_option('bia_whatsapp');
    delete_option('bia_cpf');
    delete_option('bia_data_nascimento');
    delete_option('bia_licenca_bia');
    add_option('bia_plugin_redirect', true);
}

function bia_plugin_desativar() {
    // Nenhuma ação
}

register_uninstall_hook(__FILE__, 'bia_plugin_uninstall');

function bia_plugin_uninstall() {
    // Nenhuma ação
}

// ============================
// REDIRECIONAMENTO
// ============================
add_action('admin_init', 'bia_verificar_dados_e_redirecionar');

function bia_verificar_dados_e_redirecionar() {
    if (!is_admin() || (defined('DOING_AJAX') && DOING_AJAX)) return;

    $pagina_atual = isset($_GET['page']) ? sanitize_text_field($_GET['page']) : '';
    $tab_atual = isset($_GET['tab']) ? sanitize_text_field($_GET['tab']) : '';

    if ($pagina_atual === 'bia-blog-infinito-automatico' && defined('BIA_PLUGIN_BLOQUEADO') && $tab_atual !== 'minha-conta') {
        wp_safe_redirect(admin_url('admin.php?page=bia-blog-infinito-automatico&tab=minha-conta'));
        exit;
    }

    if ($pagina_atual === 'bia-blog-infinito-automatico' && $tab_atual !== 'minha-conta') {
        $campos = [
            get_option('bia_gpt_dalle_key'),
            get_option('bia_nome_completo'),
            get_option('bia_email_compra'),
            get_option('bia_whatsapp'),
            get_option('bia_cpf'),
            get_option('bia_data_nascimento'),
        ];

        if (in_array('', array_map('trim', $campos), true)) {
            wp_safe_redirect(admin_url('admin.php?page=bia-blog-infinito-automatico&tab=minha-conta'));
            exit;
        }
    }
}
