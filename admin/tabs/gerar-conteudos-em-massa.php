<?php
/**
 * Função para gerar conteúdos em massa.
 */
add_action('wp_ajax_gerar_conteudos_em_massa', 'gerar_conteudos_em_massa');
function gerar_conteudos_em_massa() {
    if (!isset($_POST['temas']) || !is_array($_POST['temas'])) {
        wp_send_json_error('Nenhum tema foi selecionado para geração de conteúdo.');
        return;
    }

    $temas_selecionados = $_POST['temas'];
    $temas_sugeridos = get_option('temas_sugeridos', array());
    $status_temas = get_option('status_temas', array());
    $resultados = array();
    $quantidade_temas = count($temas_selecionados);

    // Definir o timeout como quantidade de temas * 60 segundos
    $timeout_dinamico = min($quantidade_temas * 300); 

    // Processar um tema por vez
    foreach ($temas_selecionados as $index) {
        if (isset($temas_sugeridos[$index])) {
            $tema = $temas_sugeridos[$index]['tema'];

            // Etapa 1: Gerar Estrutura do Artigo
            $estrutura = gerar_estrutura_para_tema($tema, $timeout_dinamico);
            if (!$estrutura) {
                error_log("Erro na geração da estrutura para o tema: $tema");
                $resultados[$index] = array(
                    'status' => 'Erro na geração da estrutura',
                    'link'   => 'N/A',
                    'index'  => $index,
                );
                continue;
            }

            // Etapa 2: Gerar Conteúdo com Base na Estrutura
            $conteudo_gerado = gerar_conteudo_com_estrutura($tema, $estrutura, $timeout_dinamico);
            if (!$conteudo_gerado) {
                error_log("Erro na geração do conteúdo para o tema: $tema");
                $resultados[$index] = array(
                    'status' => 'Erro na geração do conteúdo',
                    'link'   => 'N/A',
                    'index'  => $index,
                );
                continue;
            }

            // Criar o post no WordPress
            $post_id = wp_insert_post(array(
                'post_title'   => wp_strip_all_tags($tema),
                'post_content' => $conteudo_gerado,
                'post_status'  => 'draft',
                'post_type'    => 'post',
            ));

            if (is_wp_error($post_id)) {
                error_log("Erro ao criar post: " . $post_id->get_error_message());
                $resultados[$index] = array(
                    'status' => 'Erro ao criar post',
                    'link'   => 'N/A',
                    'index'  => $index,
                );
                continue;
            }

            // Atualizar status e salvar no banco de dados
            $status_temas[$index] = 'Produzido';
            $temas_sugeridos[$index]['post_id'] = $post_id;
            $temas_sugeridos[$index]['link'] = get_permalink($post_id);

            $resultados[$index] = array(
                'status' => 'Produzido',
                'link'   => get_permalink($post_id),
                'index'  => $index,
            );

            // Atualizações incrementais
            update_option('temas_sugeridos', $temas_sugeridos);
            update_option('status_temas', $status_temas);
        } else {
            error_log("Tema não encontrado: $index");
            $resultados[$index] = array(
                'status' => 'Tema não encontrado',
                'link'   => 'N/A',
                'index'  => $index,
            );
        }
    }

    wp_send_json_success($resultados);
    wp_die();
}

/**
 * Etapa 1: Função auxiliar para gerar estrutura do artigo.
 */
function gerar_estrutura_para_tema($tema, $timeout_dinamico) {
    $api_key = get_option('bia_gpt_dalle_key');
    if (empty($api_key)) {
        error_log('Chave API OpenAI não configurada.');
        return false;
    }

    $prompt = "Crie uma estrutura detalhada para um artigo com 3.000 palavras sobre o tema: \"$tema\". 
    Inclua títulos (H1, H2, H3), introdução, tópicos principais e conclusão.";

    $args = array(
        'headers' => array(
            'Content-Type'  => 'application/json',
            'Authorization' => 'Bearer ' . $api_key,
        ),
        'body' => json_encode(array(
            'model'       => 'gpt-4o-mini', // Pode ajustar para gpt-4, se necessário
            'messages'    => array(
                array('role' => 'system', 'content' => 'Você é um especialista em SEO e criação de conteúdos.'),
                array('role' => 'user', 'content' => $prompt),
            ),
            'max_tokens'  => 5000,
            'temperature' => 0.9,
        )),
        'timeout' => $timeout_dinamico,
    );

    $response = wp_remote_post('https://api.openai.com/v1/chat/completions', $args);

    if (is_wp_error($response)) {
        error_log('Erro na requisição OpenAI (Estrutura): ' . $response->get_error_message());
        return false;
    }

    $body = wp_remote_retrieve_body($response);
    $result = json_decode($body, true);

    if (!isset($result['choices'][0]['message']['content'])) {
        error_log('Erro: Resposta da API OpenAI (Estrutura) não contém conteúdo.');
        return false;
    }

    return trim($result['choices'][0]['message']['content']);
}

/**
 * Etapa 2: Função auxiliar para gerar conteúdo usando a estrutura do artigo.
 */
function gerar_conteudo_com_estrutura($tema, $estrutura, $timeout_dinamico) {
    $api_key = get_option('bia_gpt_dalle_key');
    if (empty($api_key)) {
        error_log('Chave API OpenAI não configurada.');
        return false;
    }

    $prompt = "Com base na estrutura a seguir, desenvolva um artigo completo sobre o tema: \"$tema\".
    Estrutura: \"$estrutura\".";

    $args = array(
        'headers' => array(
            'Content-Type'  => 'application/json',
            'Authorization' => 'Bearer ' . $api_key,
        ),
        'body' => json_encode(array(
            'model'       => 'gpt-4o-mini', // Pode ajustar para gpt-4, se necessário
            'messages'    => array(
                array('role' => 'system', 'content' => 'Você é um especialista em SEO e criação de conteúdos.'),
                array('role' => 'user', 'content' => $prompt),
            ),
            'max_tokens'  => 3000,
            'temperature' => 0.7,
        )),
        'timeout' => $timeout_dinamico,
    );

    $response = wp_remote_post('https://api.openai.com/v1/chat/completions', $args);

    if (is_wp_error($response)) {
        error_log('Erro na requisição OpenAI (Conteúdo): ' . $response->get_error_message());
        return false;
    }

    $body = wp_remote_retrieve_body($response);
    $result = json_decode($body, true);

    if (!isset($result['choices'][0]['message']['content'])) {
        error_log('Erro: Resposta da API OpenAI (Conteúdo) não contém conteúdo.');
        return false;
    }

    return trim($result['choices'][0]['message']['content']);
}