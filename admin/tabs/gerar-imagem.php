<?php
// ===================================
// FUNÇÃO PARA GERAR IMAGEM - BIA - Blog Infinito Automático
// ===================================

// Registrar a ação AJAX para gerar imagem
add_action('wp_ajax_gerar_imagem', 'gerar_imagem');

// Função para gerar imagem
function gerar_imagem() {
    if (!isset($_POST['tema_index'])) {
        wp_send_json_error('Índice do tema não foi enviado.');
        return;
    }

    $tema_index = sanitize_text_field($_POST['tema_index']);
    $temas_sugeridos = get_option('temas_sugeridos', array());

    if (!isset($temas_sugeridos[$tema_index])) {
        wp_send_json_error('Tema não encontrado.');
        return;
    }

    $tema = sanitize_text_field($temas_sugeridos[$tema_index]['tema']);
    $palavra_chave = get_option('palavras_chave_foco', '');
    $post_id = $temas_sugeridos[$tema_index]['post_id'] ?? null;

    if (!$post_id) {
        wp_send_json_error('ID do post não encontrado. Certifique-se de que o conteúdo foi gerado.');
        return;
    }

    // Obter o conteúdo e o primeiro parágrafo
    $post = get_post($post_id);
    $primeiro_paragrafo = '';

    if ($post) {
        $conteudo = apply_filters('the_content', $post->post_content);
        $paragrafos = explode("\n", strip_tags($conteudo));
        $primeiro_paragrafo = trim($paragrafos[0]);
    }

// Etapa 1: Gerar o prompt detalhado para a imagem
$prompt_detalhado = obter_prompt_detalhado_imagem($tema, $palavra_chave, $primeiro_paragrafo);

if (!$prompt_detalhado) {
    wp_send_json_error('Erro ao gerar o prompt detalhado para a imagem.');
    return;
}
// Testar a geração do prompt detalhado
error_log("Prompt Detalhado: " . $prompt_detalhado);
if (!$prompt_detalhado) {
    wp_send_json_error('Erro: Prompt detalhado vazio ou inválido.');
    return;
}

// Etapa 2: Gerar o prompt final para a imagem
$prompt_final_imagem = gerar_imagem_com_prompt($prompt_detalhado);

if (!$prompt_final_imagem) {
    wp_send_json_error('Erro ao gerar o prompt final para a imagem.');
    return;
}

// Obter a chave da API DALL-E
$api_key = get_option('bia_gpt_dalle_key');

if (!$api_key) {
    wp_send_json_error('A chave API DALL-E não foi configurada corretamente.');
    return;
}
// Log do prompt final gerado
error_log("Prompt Final Enviado ao DALL-E: " . $prompt_final_imagem);

// Fazer a requisição para a API DALL-E com o prompt final
$response = wp_remote_post('https://api.openai.com/v1/images/generations', array(
    'headers' => array(
        'Content-Type' => 'application/json',
        'Authorization' => 'Bearer ' . $api_key,
    ),
    'body' => json_encode(array(
        'prompt' => $prompt_final_imagem,
        'n' => 1,
        'size' => '1024x1024',
    )),
    'timeout' => 300, // Timeout aumentado para evitar falhas
));

if (is_wp_error($response)) {
    error_log('Erro na requisição ao DALL-E: ' . $response->get_error_message());
    wp_send_json_error('Erro na requisição ao DALL-E: ' . $response->get_error_message());
    return;
}

$body = wp_remote_retrieve_body($response);
$result = json_decode($body, true);

$body = wp_remote_retrieve_body($response);
$result = json_decode($body, true);

// Log da resposta completa da API DALL-E para diagnóstico
if ($result === null) {
    error_log("Erro ao decodificar a resposta da API DALL-E: " . $body);
    wp_send_json_error('Erro ao interpretar a resposta da API DALL-E.');
    return;
}

error_log("Resposta completa da API DALL-E: " . print_r($result, true));

if (isset($result['data'][0]['url'])) {
    $imagem_url = $result['data'][0]['url'];

    // Anexar a imagem ao post
    if (function_exists('anexar_imagem_ao_post')) {
        $image_id = anexar_imagem_ao_post($imagem_url, $post_id, $tema);

        if ($image_id) {
            $temas_sugeridos[$tema_index]['imagem'] = $imagem_url;
            update_option('temas_sugeridos', $temas_sugeridos);

            wp_send_json_success(array('imagem_url' => $imagem_url));
        } else {
            error_log('Erro ao anexar a imagem ao post.');
            wp_send_json_error('Erro ao anexar a imagem ao post.');
        }
    } else {
        error_log('Função anexar_imagem_ao_post não encontrada.');
        wp_send_json_error('Função anexar_imagem_ao_post não encontrada.');
    }
} else {
    error_log("Erro ao gerar imagem: Resposta da API não contém URL. Resposta: " . print_r($result, true));
    wp_send_json_error('Erro ao gerar imagem: Resposta da API não contém URL.');
}
}