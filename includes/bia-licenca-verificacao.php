<?php
// ============================
// BLOQUEIO DO PLUGIN SE O E-MAIL NÃO FOR VÁLIDO
// ============================

// add_action('admin_init', 'bia_verificar_email_licenca'); // Verificação desativada

function bia_verificar_email_licenca() {
    if (!is_admin() || (defined('DOING_AJAX') && DOING_AJAX)) return;

    $email = get_option('bia_email_compra');

    if (!$email || !is_email($email)) {
        if (!defined('BIA_PLUGIN_BLOQUEADO')) define('BIA_PLUGIN_BLOQUEADO', true);
        return;
    }

    $endpoint = 'https://bloginfinitoautomatico.com.br/wp-json/bia/v1/verificar-email';

    $response = wp_remote_post($endpoint, [
        'method'  => 'POST',
        'headers' => ['Content-Type' => 'application/json'],
        'body'    => json_encode([
            'email'   => $email,
            'dominio' => home_url(),
        ]),
        'timeout' => 10,
    ]);

    if (is_wp_error($response)) {
        if (!defined('BIA_PLUGIN_BLOQUEADO')) define('BIA_PLUGIN_BLOQUEADO', true);
        return;
    }

    $body_raw = wp_remote_retrieve_body($response);
    if (empty($body_raw)) {
        if (!defined('BIA_PLUGIN_BLOQUEADO')) define('BIA_PLUGIN_BLOQUEADO', true);
        return;
    }

    $body = json_decode($body_raw, true);
    if (!is_array($body) || !isset($body['status']) || $body['status'] !== 'success') {
        if (!defined('BIA_PLUGIN_BLOQUEADO')) define('BIA_PLUGIN_BLOQUEADO', true);
    }
}