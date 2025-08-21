<?php
// ===================================
// FUNÇÕES PARA PRODUZIR CONTEÚDOS - BIA - Blog Infinito Automático
// ===================================

// Incluir o arquivo de prompts
include_once(plugin_dir_path(__FILE__) . 'prompt.php');

// Incluir a função para gerar conteúdos em massa
include_once(plugin_dir_path(__FILE__) . 'gerar-conteudos-em-massa.php');

// Função para gerar conteúdo unificada com a geração de imagem
add_action('wp_ajax_gerar_conteudo', 'gerar_conteudo');
function gerar_conteudo() {
    if (!isset($_POST['tema_index'])) {
        wp_send_json_error('Índice do tema não foi enviado.');
        return;
    }

    $tema_index = sanitize_text_field($_POST['tema_index']);
    $temas_sugeridos = get_option('temas_sugeridos', array());
    $status_temas = get_option('status_temas', array());

    if (!is_array($temas_sugeridos) || empty($temas_sugeridos)) {
        wp_send_json_error('Nenhum tema sugerido foi encontrado. O array de temas está vazio.');
        return;
    }

    if (!array_key_exists($tema_index, $temas_sugeridos)) {
        wp_send_json_error('Tema não encontrado no array de sugestões. Índice inválido: ' . $tema_index);
        return;
    }

    if (!isset($temas_sugeridos[$tema_index]['tema'])) {
        wp_send_json_error('O tema não está definido no array de temas sugeridos.');
        return;
    }

    $tema = sanitize_text_field($temas_sugeridos[$tema_index]['tema']);
    $nicho = get_option('nicho_blog', '');
    $palavras_chave = get_option('palavras_chave_foco', '');
    
    // Obter o idioma do tema sugerido, com fallback para Português
    $idioma = isset($temas_sugeridos[$tema_index]['idioma']) ? $temas_sugeridos[$tema_index]['idioma'] : 'Portugues';

    if (empty($nicho) || empty($palavras_chave)) {
        wp_send_json_error('Nicho ou palavras-chave não encontrados. Certifique-se de fornecer essas informações corretamente.');
        return;
    }

    // Usando a mesma chave bia_gpt_dalle_key para conteúdo e imagens
    $api_key = get_option('bia_gpt_dalle_key');

    if (!$api_key) {
        wp_send_json_error('A chave API GPT-4 não foi configurada corretamente.');
        return;
    }

    // Passar o idioma para a função de obter prompt
    $prompt = obter_prompt_gerar_conteudo($tema, $nicho, $palavras_chave, $idioma);
    error_log('Prompt enviado para a API: ' . $prompt);

    
    $args = array(
        'headers' => array(
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $api_key,
        ),
        'body' => json_encode(array(
            'model' => 'gpt-4o-mini',
            'messages' => array(
                array('role' => 'system', 'content' => 'Você é um assistente especialista em SEO e copywriting técnico avançado.'),
                array('role' => 'user', 'content' => $prompt),
            ),
            'max_tokens' => 5000,
            'temperature' => 0.9,
        )),
        'timeout' => 800, 
    );

    $response = null; // Inicializa a variável $response
    for ($i = 0; $i < 3; $i++) {
        $response = wp_remote_post('https://api.openai.com/v1/chat/completions', $args);

        if (is_wp_error($response)) {
            error_log('Erro na requisição: ' . $response->get_error_message());
            if ($i == 2) {
                wp_send_json_error('Erro ao gerar conteúdo: ' . $response->get_error_message());
                return;
            }
            sleep(2);
            continue;
        }

        $body = wp_remote_retrieve_body($response);
        $result = json_decode($body, true);

        if (isset($result['choices']) && !empty($result['choices'])) {
            $conteudo_formatado = trim($result['choices'][0]['message']['content']);
            error_log('Conteúdo gerado: ' . $conteudo_formatado);
            if (!empty($conteudo_formatado)) {
                break;
            }
        } else {
            if ($i == 2) {
                wp_send_json_error('Erro ao gerar conteúdo: indefinido.');
                return;
            }
            sleep(2);
        }
    }

    if (empty($conteudo_formatado)) {
        wp_send_json_error('Erro ao gerar conteúdo.');
        return;
    }

    // Obter autor, categorias e tags do tema sugerido
    $autor_id = isset($temas_sugeridos[$tema_index]['autor_id']) ? intval($temas_sugeridos[$tema_index]['autor_id']) : 0;
    $categorias = isset($temas_sugeridos[$tema_index]['categorias']) ? $temas_sugeridos[$tema_index]['categorias'] : array();
    $tags = isset($temas_sugeridos[$tema_index]['tags']) ? $temas_sugeridos[$tema_index]['tags'] : '';
    
    // Obter dados do CTA
$cta_titulo    = isset($temas_sugeridos[$tema_index]['cta_titulo']) ? $temas_sugeridos[$tema_index]['cta_titulo'] : '';
$cta_descricao = isset($temas_sugeridos[$tema_index]['cta_descricao']) ? $temas_sugeridos[$tema_index]['cta_descricao'] : '';
$cta_botao     = isset($temas_sugeridos[$tema_index]['cta_botao']) ? $temas_sugeridos[$tema_index]['cta_botao'] : '';
$cta_link      = isset($temas_sugeridos[$tema_index]['cta_link']) ? $temas_sugeridos[$tema_index]['cta_link'] : '';
$cta_imagem    = isset($temas_sugeridos[$tema_index]['cta_imagem']) ? $temas_sugeridos[$tema_index]['cta_imagem'] : '';

    // Adicionar CTA ao final do conteúdo se pelo menos um dos campos estiver preenchido
if (!empty($cta_titulo) || !empty($cta_descricao) || !empty($cta_botao) || !empty($cta_link) || !empty($cta_imagem)) {
$conteudo_formatado .= gerar_html_cta($cta_titulo, $cta_descricao, $cta_botao, $cta_link, $cta_imagem);
}


    // Preparar dados do post com autor
    $post_data = array(
        'post_title' => wp_strip_all_tags($tema),
        'post_content' => $conteudo_formatado,
        'post_status' => 'draft',
        'post_type' => 'post',
    );

    // Adicionar autor apenas se estiver definido
    if ($autor_id > 0) {
        $post_data['post_author'] = $autor_id;
    }

    // Inserindo o conteúdo no post
    $post_id = wp_insert_post($post_data);

    if ($post_id) {
        // Atribuir categorias ao post se existirem
        if (!empty($categorias)) {
            wp_set_post_categories($post_id, $categorias);
        }

        // Atribuir tags ao post se existirem
        if (!empty($tags)) {
            $tags_array = array_map('trim', explode(',', $tags));
            wp_set_post_tags($post_id, $tags_array);
        }

        // Atualizar o status do tema e o post gerado
        $status_temas[$tema_index] = 'Produzido';
        $temas_sugeridos[$tema_index]['post_id'] = $post_id;
        $temas_sugeridos[$tema_index]['link'] = get_permalink($post_id);

        // Geração de imagem após o conteúdo ser produzido
        // Adaptar o prompt de imagem para o idioma selecionado
        $prompt_imagem = obter_prompt_imagem($tema, $palavras_chave, strip_tags($conteudo_formatado), $idioma);
        $imagem_url = gerar_imagem_dalle($prompt_imagem, $api_key);

        if ($imagem_url) {
            anexar_imagem_ao_post($imagem_url, $post_id, $tema);
            $temas_sugeridos[$tema_index]['imagem'] = $imagem_url;
        } else {
            error_log('Erro ao gerar imagem: URL não obtida.');
        }

        update_option('temas_sugeridos', $temas_sugeridos);
        update_option('status_temas', $status_temas);

        wp_send_json_success(array(
            'post_id' => $post_id,
            'link' => get_permalink($post_id),
            'imagem_url' => isset($imagem_url) ? $imagem_url : null
        ));
    } else {
        wp_send_json_error('Erro ao salvar o conteúdo como rascunho.');
    }
}

// Incluir CTA no artigo // 


function gerar_html_cta($titulo, $descricao, $botao, $link, $imagem) {
    if (empty($titulo) && empty($descricao) && empty($botao) && empty($link) && empty($imagem)) {
        return '';
    }

    $html = "\n\n";
    $html .= '<div class="wp-block-group aligncenter" style="margin: 30px 0; text-align: center;">';

    // Imagem (com ou sem link)
    if (!empty($imagem)) {
        $html .= '<div style="margin-bottom: 15px;">';
        if (!empty($link)) {
            $html .= '<a href="' . esc_url($link) . '" target="_blank" rel="noopener noreferrer">';
            $html .= '<img src="' . esc_url($imagem) . '" alt="' . esc_attr($titulo) . '" style="max-width: 100%; height: auto; border-radius: 4px;">';
            $html .= '</a>';
        } else {
            $html .= '<img src="' . esc_url($imagem) . '" alt="' . esc_attr($titulo) . '" style="max-width: 100%; height: auto; border-radius: 4px;">';
        }
        $html .= '</div>';
    }

    // Título
    if (!empty($titulo)) {
        $html .= '<h3 style="font-size: 22px; margin-bottom: 10px;">' . esc_html($titulo) . '</h3>';
    }

    // Descrição
    if (!empty($descricao)) {
        $html .= '<p style="font-size: 16px; color: #555; margin-bottom: 20px;">' . esc_html($descricao) . '</p>';
    }

    // Botão
    if (!empty($botao) && !empty($link)) {
        $html .= '<div class="wp-block-button aligncenter">';
        $html .= '<a class="wp-block-button__link" href="' . esc_url($link) . '" target="_blank" rel="noopener noreferrer">' . esc_html($botao) . '</a>';
        $html .= '</div>';
    }

    $html .= '</div>';
    return $html;
}




// ===================================
// AJAX: Atualizar tema editado
// ===================================

add_action('wp_ajax_atualizar_tema', 'bia_atualizar_tema_callback');

function bia_atualizar_tema_callback() {
    if (!current_user_can('edit_posts')) {
        wp_send_json_error('Permissão negada.');
    }

    $tema_index = isset($_POST['tema_index']) ? sanitize_text_field($_POST['tema_index']) : '';
    $novo_tema = isset($_POST['novo_tema']) ? sanitize_text_field($_POST['novo_tema']) : '';

    if ($tema_index === '' || $novo_tema === '') {
        wp_send_json_error('Dados incompletos.');
    }

    $temas_sugeridos = get_option('temas_sugeridos', array());

    if (!isset($temas_sugeridos[$tema_index])) {
        wp_send_json_error('Tema não encontrado.');
    }

    $temas_sugeridos[$tema_index]['tema'] = $novo_tema;
    update_option('temas_sugeridos', $temas_sugeridos);

    wp_send_json_success('Tema atualizado com sucesso.');
}


// ===================================
// Função para gerar prompt de imagem realista - BIA
// ===================================

function obter_prompt_imagem($tema, $palavras_chave, $primeiro_paragrafo = '', $idioma = 'Português') {
    return "

Crie uma imagem que represente $tema
Considere integrar elementos que lembrem $palavras_chave
A imagem deve ser ultra profissional e ultra realista
Evite usar texto na imagem
Importante: o artigo foi produzido em $idioma. 
";
}



// Função para gerar imagem usando a API DALL-E 3
function gerar_imagem_dalle($prompt, $api_key) {
    $response = wp_remote_post('https://api.openai.com/v1/images/generations', array(
        'headers' => array(
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $api_key,
        ),
        'body' => json_encode(array(
            'prompt' => $prompt,
            'model' => 'dall-e-3', // Especificação do modelo DALL-E 3
            'n' => 1, // Número de imagens a serem geradas
            'size' => '1024x1024', // Tamanho da imagem
            'response_format' => 'url', // Formato de resposta (URL para imagem gerada)
        )),
        'timeout' => 90, // Timeout ajustado para maior tempo de processamento
    ));

    if (is_wp_error($response)) {
        error_log('Erro na requisição de imagem: ' . $response->get_error_message());
        return false;
    }

    $body = wp_remote_retrieve_body($response);
    $result = json_decode($body, true);

    // Log para depuração
    error_log('Resposta completa da API DALL-E 3: ' . print_r($result, true));

    if (isset($result['data'][0]['url'])) {
        return $result['data'][0]['url'];
    }

    return false;
}

// Função para anexar imagem ao post
function anexar_imagem_ao_post($image_url, $post_id, $tema) {
    $upload_dir = wp_upload_dir();
    $image_data = file_get_contents($image_url);

    if ($image_data === false) {
        error_log('Erro ao baixar a imagem.');
        return false;
    }

    $filename = sanitize_file_name($tema) . '.png';
    $file = $upload_dir['path'] . '/' . $filename;

    if (file_put_contents($file, $image_data) === false) {
        error_log('Erro ao salvar a imagem no diretório de uploads.');
        return false;
    }

    $wp_filetype = wp_check_filetype($filename, null);

    if (!$wp_filetype['type']) {
        error_log('Tipo de arquivo não suportado: ' . $filename);
        return false;
    }

    $attachment = array(
        'post_mime_type' => $wp_filetype['type'],
        'post_title' => sanitize_file_name($filename),
        'post_content' => '',
        'post_status' => 'inherit'
    );

    $attach_id = wp_insert_attachment($attachment, $file, $post_id);

    if (is_wp_error($attach_id)) {
        error_log('Erro ao inserir anexo: ' . $attach_id->get_error_message());
        return false;
    }

    require_once(ABSPATH . 'wp-admin/includes/image.php');
    $attach_data = wp_generate_attachment_metadata($attach_id, $file);
    wp_update_attachment_metadata($attach_id, $attach_data);

    if (!set_post_thumbnail($post_id, $attach_id)) {
        error_log('Erro ao definir a imagem destacada para o post ID: ' . $post_id);
        return false;
    }

    return $attach_id;
}

// Função para mover tema para o histórico após a publicação
function mover_para_historico($tema_index) {
    $temas_sugeridos = get_option('temas_sugeridos', array());
    $temas_historico = get_option('temas_historico', array());

    if (isset($temas_sugeridos[$tema_index])) {
        $tema = $temas_sugeridos[$tema_index];
        $tema['data'] = current_time('mysql'); // Adiciona a data de movimentação para o histórico

        $temas_historico[] = $tema;
        update_option('temas_historico', $temas_historico);

        // Remove o tema dos sugeridos
        unset($temas_sugeridos[$tema_index]);
        update_option('temas_sugeridos', $temas_sugeridos);
    }
}

// Função para publicar tema individual
add_action('wp_ajax_publicar_tema', 'publicar_tema');
function publicar_tema() {
    if (!isset($_POST['tema_index'])) {
        wp_send_json_error('Índice do tema não foi enviado.');
        return;
    }

    $tema_index = sanitize_text_field($_POST['tema_index']);
    $temas_sugeridos = get_option('temas_sugeridos', array());

    if (isset($temas_sugeridos[$tema_index])) {
        if (get_option('status_temas')[$tema_index] !== 'Produzido') {
            wp_send_json_error('O conteúdo deve ser gerado antes de ser publicado.');
            return;
        }

        $post_id = $temas_sugeridos[$tema_index]['post_id'];

        wp_update_post(array(
            'ID' => $post_id,
            'post_status' => 'publish'
        ));

        $status_temas = get_option('status_temas', array());
        $status_temas[$tema_index] = 'Publicado';
        update_option('status_temas', $status_temas);

        // Mover para o histórico após a publicação
        mover_para_historico($tema_index);

        wp_send_json_success(array('link' => get_permalink($post_id)));
    } else {
        wp_send_json_error('Tema não encontrado.');
    }
}

// Função para publicar temas em massa
add_action('wp_ajax_publicar_temas_em_massa', 'publicar_temas_em_massa');
function publicar_temas_em_massa() {
    if (!isset($_POST['temas']) || !is_array($_POST['temas'])) {
        wp_send_json_error('Nenhum tema foi selecionado para publicação.');
        return;
    }

    $temas_selecionados = $_POST['temas'];
    $temas_sugeridos = get_option('temas_sugeridos', array());
    $status_temas = get_option('status_temas', array());
    $temas_publicados = 0;

    foreach ($temas_selecionados as $index) {
        if (isset($temas_sugeridos[$index]) && $status_temas[$index] === 'Produzido') {
            $post_id = $temas_sugeridos[$index]['post_id'];

            wp_update_post(array(
                'ID' => $post_id,
                'post_status' => 'publish'
            ));

            $status_temas[$index] = 'Publicado';
            
            // Mover para o histórico após a publicação
            mover_para_historico($index);
            
            $temas_publicados++;
        }
    }

    update_option('status_temas', $status_temas);

    if ($temas_publicados > 0) {
        wp_send_json_success('Temas publicados com sucesso: ' . $temas_publicados);
    } else {
        wp_send_json_error('Nenhum tema foi publicado. Verifique se os temas selecionados foram produzidos.');
    }
}

// Função para excluir tema individual
// Função para excluir tema individual e mover para aba "Excluídos"
add_action('wp_ajax_excluir_tema', 'excluir_tema');
function excluir_tema() {
    if (!isset($_POST['tema_index'])) {
        wp_send_json_error('Índice do tema não foi enviado.');
        return;
    }

    $tema_index = sanitize_text_field($_POST['tema_index']);
    $temas_sugeridos = get_option('temas_sugeridos', array());
    $status_temas = get_option('status_temas', array());
    $temas_excluidos = get_option('temas_excluidos', array());

    if (isset($temas_sugeridos[$tema_index])) {
        $tema = $temas_sugeridos[$tema_index];
        $tema['status'] = $status_temas[$tema_index] ?? 'Pendente';
        $tema['data'] = current_time('mysql');

        $temas_excluidos[] = $tema;
        update_option('temas_excluidos', $temas_excluidos);

        if (isset($tema['post_id'])) {
            wp_delete_post($tema['post_id'], true);
        }

        unset($temas_sugeridos[$tema_index]);
        unset($status_temas[$tema_index]);

        update_option('temas_sugeridos', $temas_sugeridos);
        update_option('status_temas', $status_temas);

        wp_send_json_success('Tema movido para aba Excluídos.');
    } else {
        wp_send_json_error('Tema não encontrado.');
    }
}

// Função para excluir temas em massa
// Função para excluir múltiplos temas e mover para aba "Excluídos"
add_action('wp_ajax_excluir_temas_em_massa', 'excluir_temas_em_massa');
function excluir_temas_em_massa() {
    if (!isset($_POST['temas']) || !is_array($_POST['temas'])) {
        wp_send_json_error('Nenhum tema selecionado.');
        return;
    }

    $temas_indices = array_map('sanitize_text_field', $_POST['temas']);
    $temas_sugeridos = get_option('temas_sugeridos', array());
    $status_temas = get_option('status_temas', array());
    $temas_excluidos = get_option('temas_excluidos', array());

    foreach ($temas_indices as $index) {
        if (isset($temas_sugeridos[$index])) {
            $tema = $temas_sugeridos[$index];
            $tema['status'] = $status_temas[$index] ?? 'Pendente';
            $tema['data'] = current_time('mysql');

            $temas_excluidos[] = $tema;

            if (isset($tema['post_id'])) {
                wp_delete_post($tema['post_id'], true);
            }

            unset($temas_sugeridos[$index]);
            unset($status_temas[$index]);
        }
    }

    update_option('temas_sugeridos', $temas_sugeridos);
    update_option('status_temas', $status_temas);
    update_option('temas_excluidos', $temas_excluidos);

    wp_send_json_success('Temas excluídos com sucesso e movidos para aba Excluídos.');
}

// Adicionar JavaScript para manipular os eventos de exclusão
add_action('admin_footer', 'bia_adicionar_js_eventos');
function bia_adicionar_js_eventos() {
    ?>
    <script type="text/javascript">
    jQuery(document).ready(function($) {
        // Variável global para controlar se a confirmação já foi mostrada
        window.confirmacaoExclusaoJaMostrada = false;
        window.bia_conteudos_produzidos = 0;
        
        // Remover quaisquer event handlers existentes para evitar duplicação
        $('.excluir-sugestao').off('click');
        $('#executar-acao-em-massa').off('click.exclusao');
        $('#selecionar-todos').off('change');
        
        // Adicionar event handler para exclusão individual
        $('.excluir-sugestao').on('click', function(e) {
            e.preventDefault();
            
            if (!confirm('Tem certeza que deseja excluir este tema?')) {
                return;
            }
            
            var tema_index = $(this).data('tema-index');
            var row = $(this).closest('tr');
            
            // Reduzir a opacidade da linha para indicar que está sendo processada
            row.css('opacity', '0.5');
            
            $.post(ajaxurl, {
                action: 'excluir_tema',
                tema_index: tema_index
            }, function(response) {
                if (response.success) {
                    // Remover a linha da tabela
                    row.fadeOut(400, function() {
                        $(this).remove();
                    });
                } else {
                    // Restaurar a opacidade e mostrar erro
                    row.css('opacity', '1');
                    alert('Erro ao excluir tema: ' + response.data);
                }
            }).fail(function(xhr, status, error) {
                // Restaurar a opacidade e mostrar erro
                row.css('opacity', '1');
                alert('Erro na requisição: ' + error);
            });
        });
        
        // Adicionar event handler para ações em massa
        $('#executar-acao-em-massa').on('click.exclusao', function(e) {
            e.preventDefault();
            
            var acao = $('#acao-em-massa').val();
var temas_selecionados = [];
$('input[name="temas[]"]:checked').each(function() {
    temas_selecionados.push($(this).val());
});

            
            if (temas_selecionados.length === 0) {
    alert('Nenhum tema foi selecionado.');
    return;
} else if (acao === 'gerar_conteudos') {
    if (!confirm('Tem certeza que deseja produzir os artigos para os temas selecionados?')) {
        return;
    }
}

            
            // Resetar a variável de confirmação
            window.confirmacaoExclusaoJaMostrada = false;
            
            if (acao === 'excluir') {
                if (!confirm('Tem certeza que deseja excluir os temas selecionados?')) {
                    return;
                }
                window.confirmacaoExclusaoJaMostrada = true;
                
                // Reduzir a opacidade das linhas selecionadas
                $('input[name="temas_selecionados[]"]:checked').closest('tr').css('opacity', '0.5');
                
                $.post(ajaxurl, {
                    action: 'excluir_temas_em_massa',
                    temas: temas_selecionados
                }, function(response) {
                    if (response.success) {
                        // Remover as linhas da tabela
                        $('input[name="temas_selecionados[]"]:checked').closest('tr').fadeOut(400, function() {
                            $(this).remove();
                        });
                        alert(response.data);
                    } else {
                        // Restaurar a opacidade e mostrar erro
                        $('input[name="temas_selecionados[]"]:checked').closest('tr').css('opacity', '1');
                        alert('Erro ao excluir temas: ' + response.data);
                    }
                }).fail(function(xhr, status, error) {
                    // Restaurar a opacidade e mostrar erro
                    $('input[name="temas_selecionados[]"]:checked').closest('tr').css('opacity', '1');
                    alert('Erro na requisição: ' + error);
                });
            } else if (acao === 'gerar_conteudos') {
                if (!confirm('Tem certeza que deseja produzir os artigos para os temas selecionados?')) {
                    return;
                }
                
                // Mostrar aviso de processamento
                $('#mass-action-warning').show();
                
                // Inicializar contador
                window.bia_conteudos_produzidos = 0;
                
                // Chamar função para gerar conteúdos em massa com contador
                gerar_conteudos_em_massa_com_contador(temas_selecionados, 0);
            } else if (acao === 'publicar') {
                if (!confirm('Tem certeza que deseja publicar os temas selecionados?')) {
                    return;
                }
                
                // Reduzir a opacidade das linhas selecionadas
                $('input[name="temas_selecionados[]"]:checked').closest('tr').css('opacity', '0.5');
                
                $.post(ajaxurl, {
                    action: 'publicar_temas_em_massa',
                    temas: temas_selecionados
                }, function(response) {
                    if (response.success) {
                        // Remover as linhas da tabela
                        $('input[name="temas_selecionados[]"]:checked').closest('tr').fadeOut(400, function() {
                            $(this).remove();
                        });
                        alert(response.data);
                    } else {
                        // Restaurar a opacidade e mostrar erro
                        $('input[name="temas_selecionados[]"]:checked').closest('tr').css('opacity', '1');
                        alert('Erro ao publicar temas: ' + response.data);
                    }
                }).fail(function(xhr, status, error) {
                    // Restaurar a opacidade e mostrar erro
                    $('input[name="temas_selecionados[]"]:checked').closest('tr').css('opacity', '1');
                    alert('Erro na requisição: ' + error);
                });
            }
        });
        
        // Adicionar event handler para selecionar/desselecionar todos
        $('#selecionar-todos').on('change', function() {
            $('input[name="temas[]"]').prop('checked', $(this).prop('checked'));
        });
    });
    
    // Função para gerar conteúdos em massa com contador
    function gerar_conteudos_em_massa_com_contador(temas_selecionados, index) {
        if (index >= temas_selecionados.length) {
            // Todos os conteúdos foram processados, mostrar notificação final
            jQuery('#mass-action-warning').hide();
            if (window.bia_conteudos_produzidos > 0) {
                alert('Conteúdos produzidos com sucesso: ' + window.bia_conteudos_produzidos);
            } else {
                alert('Nenhum conteúdo foi produzido.');
            }
            return;
        }
        
        var tema_index = temas_selecionados[index];
        
        // Reduzir a opacidade da linha atual
        jQuery('input[value="' + tema_index + '"]').closest('tr').css('opacity', '0.5');
        
        // Chamar a função original de geração de conteúdo
        jQuery.post(ajaxurl, {
            action: 'gerar_conteudo',
            tema_index: tema_index
        }, function(response) {
            var row = jQuery('input[value="' + tema_index + '"]').closest('tr');
            
            if (response.success) {
                // Atualizar o status na tabela
                row.find('.coluna-status').text('Produzido');
                
                // Adicionar link para visualizar o post
                var link_col = row.find('.coluna-acoes');
                if (link_col.find('.visualizar-post').length === 0) {
                    link_col.prepend('<a href="' + response.data.link + '" target="_blank" class="visualizar-post">Visualizar</a> | ');
                }
                
                // Restaurar a opacidade
                row.css('opacity', '1');
                
                // Incrementar o contador de conteúdos produzidos
                window.bia_conteudos_produzidos++;
            } else {
                // Restaurar a opacidade e mostrar erro
                row.css('opacity', '1');
                console.error('Erro ao gerar conteúdo para o tema ' + tema_index + ': ' + response.data);
            }
            
            // Processar o próximo tema
            gerar_conteudos_em_massa_com_contador(temas_selecionados, index + 1);
        }).fail(function(xhr, status, error) {
            // Restaurar a opacidade e mostrar erro
            jQuery('input[value="' + tema_index + '"]').closest('tr').css('opacity', '1');
            console.error('Erro na requisição para o tema ' + tema_index + ': ' + error);
            
            // Processar o próximo tema mesmo em caso de falha
            gerar_conteudos_em_massa_com_contador(temas_selecionados, index + 1);
        });
    }
    </script>
    
    <?php
}