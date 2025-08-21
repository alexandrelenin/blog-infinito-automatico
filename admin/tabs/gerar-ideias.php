<?php
function bia_gerar_ideias() {
    // Obter valores salvos para preencher os campos
$nicho_blog = '';
$palavras_chave_foco = '';
$idioma_conteudo_padrao = 'Portugues';
$autor_posts_padrao = 0;
$categorias_posts_padrao = array();
$tags_posts_padrao = '';
$cta_texto_padrao = '';
$cta_link_padrao = '';
$cta_imagem_padrao = '';

    
    // Adicionar estilos CSS para o novo layout
    ?>
    <style>

/* Estilos gerais */

.bia-cta-button {
  display: inline-block;
  padding: 10px 20px;
  background: var(--bia-primary-color, #007cba);
  color: #fff;
  text-decoration: none;
  border-radius: 4px;
  font-weight: 500;
  transition: background 0.3s ease;
}

.bia-cta-button:hover {
  filter: brightness(0.9);
}

/* Estilos para simular o botão nativo do WordPress */
.wp-block-button {
  margin-top: 10px;
}

.wp-block-button__link {
  display: inline-block;
  padding: 0.5em 1em;
  color: #fff !important;
  background-color: #32373c;
  border: none;
  border-radius: 4px;
  font-size: 16px;
  font-weight: 500;
  text-align: center;
  text-decoration: none !important;
  cursor: pointer;
  box-shadow: none;
  transition: background-color 0.2s ease;
}

.wp-block-button__link:hover {
  background-color: #444;
  color: #fff;
}

/* Estilo para botão primário (tema) */
.wp-block-button.is-style-fill .wp-block-button__link {
  background-color: var(--bia-primary-color, #0073aa);
}

.wp-block-button.is-style-fill .wp-block-button__link:hover {
  filter: brightness(0.9);
}
         
        .bia-submit-button {
    background: #8c52ff;
    border-color: #8c52ff;
    color: #fff;
    padding: 10px 20px;
    font-size: 14px;
    font-weight: 500;
    border-radius: 20px;
    cursor: pointer;
    transition: all 0.3s ease;
    display: inline-flex;
    align-items: center;
    border: none;
    box-shadow: 0 4px 10px rgba(140, 82, 255, 0.3);
}

.bia-submit-button:hover {
    background: #7b48e5;
    transform: translateY(-2px);
    box-shadow: 0 6px 15px rgba(140, 82, 255, 0.4);
}



        .bia-container {
            max-width: 100%;
            margin: 20px 0;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Oxygen-Sans, Ubuntu, Cantarell, "Helvetica Neue", sans-serif;
        }
        
        .bia-card {
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            padding: 20px;
            margin-bottom: 20px;
            border: 1px solid #e2e4e7;
            transition: box-shadow 0.3s ease;
        }
        
        .bia-card:hover {
            box-shadow: 0 4px 12px rgba(0,0,0,0.12);
        }
        
        .bia-card-header {
            border-bottom: 1px solid #f0f0f0;
            margin: -20px -20px 20px;
            padding: 15px 20px;
            display: flex;
            align-items: center;
            background: #f9f9f9;
            border-radius: 8px 8px 0 0;
        }
        
        .bia-card-header h3 {
            margin: 0;
            font-size: 16px;
            font-weight: 600;
            color: #23282d;
        }
        
        .bia-card-header .dashicons {
            margin-right: 10px;
            color: #8c52ff;
        }
        
        .bia-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            grid-gap: 20px;
        }
        
        .bia-form-group {
            margin-bottom: 15px;
        }
        
        .bia-form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: 500;
            color: #23282d;
        }
        
        .bia-form-group input[type="text"],
        .bia-form-group input[type="number"],
        .bia-form-group input[type="url"],
        .bia-form-group select,
        .bia-form-group textarea {
            width: 100%;
            padding: 8px 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-shadow: inset 0 1px 2px rgba(0,0,0,0.07);
            transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
        }
        
        .bia-form-group input[type="text"]:focus,
        .bia-form-group input[type="number"]:focus,
        .bia-form-group input[type="url"]:focus,
        .bia-form-group select:focus,
        .bia-form-group textarea:focus {
            border-color: #8c52ff;
            box-shadow: 0 0 0 1px #8c52ff;
            outline: 2px solid transparent;
        }
        
        .bia-help-text {
            font-size: 12px;
            color: #666;
            margin-top: 5px;
        }
        
        .bia-tooltip {
            position: relative;
            display: inline-block;
            margin-left: 5px;
            color: #8c52ff;
            cursor: help;
        }
        
        .bia-tooltip .dashicons {
            font-size: 16px;
            width: 16px;
            height: 16px;
        }
        
        .bia-tooltip .bia-tooltip-text {
            visibility: hidden;
            width: 200px;
            background-color: #23282d;
            color: #fff;
            text-align: center;
            border-radius: 4px;
            padding: 8px;
            position: absolute;
            z-index: 1;
            bottom: 125%;
            left: 50%;
            margin-left: -100px;
            opacity: 0;
            transition: opacity 0.3s;
            font-size: 12px;
            line-height: 1.4;
        }
        
        .bia-tooltip .bia-tooltip-text::after {
            content: "";
            position: absolute;
            top: 100%;
            left: 50%;
            margin-left: -5px;
            border-width: 5px;
            border-style: solid;
            border-color: #23282d transparent transparent transparent;
        }
        
        .bia-tooltip:hover .bia-tooltip-text {
            visibility: visible;
            opacity: 1;
        }
        
        .bia-categorias-container {
            max-height: 200px;
            overflow-y: auto;
            border: 1px solid #ddd;
            border-radius: 4px;
            padding: 10px;
            background: #f9f9f9;
        }
        
        .bia-categorias-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            grid-gap: 0;
            column-gap: 10px;
        }
        
        .bia-categoria-item {
            margin-bottom: 3px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        
        .bia-submit-container {
            margin-top: 20px;
            text-align: right;
        }
        
        .bia-progress {
            margin-top: 20px;
            display: none;
        }
        
        .bia-progress-bar {
            height: 10px;
            background-color: #f0f0f0;
            border-radius: 5px;
            overflow: hidden;
        }
        
        .bia-progress-bar-fill {
            height: 100%;
            background-color: #8c52ff;
            width: 0%;
            transition: width 0.5s ease;
        }
        
        .bia-preview {
            background: #f9f9f9;
            border: 1px dashed #ddd;
            border-radius: 4px;
            padding: 15px;
            margin-top: 15px;
        }
        
        .bia-preview h4 {
            margin-top: 0;
            color: #23282d;
            font-size: 14px;
        }
        
        .bia-preview-content {
            font-style: italic;
            color: #666;
        }
        
        /* Responsividade */
        @media (max-width: 782px) {
            .bia-grid {
                grid-template-columns: 1fr;
            }
            
            .bia-categorias-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }
        <?php
$cor_primaria = get_theme_mod('primary_color', '#007cba');
?>

    </style>
    
    <div class="wrap bia-container">
        <h2>Gerar Ideias</h2>
        
        <form method="post" id="form_gerar_ideias">
            <!-- Seção: Informações Básicas -->
            <div class="bia-card">
                <div class="bia-card-header">
                    <span class="dashicons dashicons-info"></span>
                    <h3>Informações Básicas</h3>
                </div>
                
                <div class="bia-grid">
                    <div class="bia-form-group">
                        <label for="nicho_blog">
                            Qual é o Seu Nicho de Negócio?
                            <span class="bia-tooltip">
                                <span class="dashicons dashicons-editor-help"></span>
                                <span class="bia-tooltip-text">Defina o nicho específico do seu blog para gerar conteúdo mais relevante e direcionado.</span>
                            </span>
                        </label>
                        <input type="text" id="nicho_blog" name="nicho_blog" value="<?php echo esc_attr($nicho_blog); ?>" required>
                    </div>
                    
                    <div class="bia-form-group">
                        <label for="palavras_chave_foco">
                            Palavras Chave Foco
                            <span class="bia-tooltip">
                                <span class="dashicons dashicons-editor-help"></span>
                                <span class="bia-tooltip-text">Insira as palavras-chave principais separadas por vírgula. Estas serão usadas para otimizar o SEO do conteúdo.</span>
                            </span>
                        </label>
                        <input type="text" id="palavras_chave_foco" name="palavras_chave_foco" value="<?php echo esc_attr($palavras_chave_foco); ?>" required>
                        <p class="bia-help-text">Separe as palavras-chave por vírgula.</p>
                    </div>
                </div>
                
                <div class="bia-grid">
                    <div class="bia-form-group">
                        <label for="quantidade">
                            Quantidade de Ideias
                            <span class="bia-tooltip">
                                <span class="dashicons dashicons-editor-help"></span>
                                <span class="bia-tooltip-text">Defina quantas ideias de conteúdo você deseja gerar nesta sessão.</span>
                            </span>
                        </label>
                        <input type="number" id="quantidade" name="quantidade" min="1" value="5" required>
                    </div>
                    
                    <div class="bia-form-group">
                        <label for="idioma_conteudo">
                            Idioma do Conteúdo
                            <span class="bia-tooltip">
                                <span class="dashicons dashicons-editor-help"></span>
                                <span class="bia-tooltip-text">Selecione o idioma em que o conteúdo será gerado.</span>
                            </span>
                        </label>
                        <select id="idioma_conteudo" name="idioma_conteudo">
                            <option value="Portugues" <?php selected($idioma_conteudo_padrao, 'Portugues'); ?>>Português</option>
                            <option value="Ingles" <?php selected($idioma_conteudo_padrao, 'Ingles'); ?>>Inglês</option>
                            <option value="Espanhol" <?php selected($idioma_conteudo_padrao, 'Espanhol'); ?>>Espanhol</option>
                            <option value="Frances" <?php selected($idioma_conteudo_padrao, 'Frances'); ?>>Francês</option>
                            <option value="Italiano" <?php selected($idioma_conteudo_padrao, 'Italiano'); ?>>Italiano</option>
                            <option value="Mandarim" <?php selected($idioma_conteudo_padrao, 'Mandarim'); ?>>Mandarim (Chinês)</option>
                            <option value="Russo" <?php selected($idioma_conteudo_padrao, 'Russo'); ?>>Russo</option>
                        </select>
                    </div>
                </div>
            </div>
                        
            <!-- Seção: Personalização -->
            <div class="bia-card">
                <div class="bia-card-header">
                    <span class="dashicons dashicons-admin-customizer"></span>
                    <h3>Personalização</h3>
                </div>
                
                <div class="bia-form-group">
                    <label for="conceito_artigo">
                        Contexto / Introdução (Opcional)
                        <span class="bia-tooltip">
                            <span class="dashicons dashicons-editor-help"></span>
                            <span class="bia-tooltip-text">Forneça contexto adicional ou diretrizes específicas para orientar a geração de conteúdo.</span>
                        </span>
                    </label>
                    <textarea id="conceito_artigo" name="conceito_artigo" rows="4"></textarea>
                    
                    <div class="bia-preview">
                        <h4>Visualização Prévia</h4>
                        <div class="bia-preview-content" id="preview-content">
                            A visualização do conteúdo aparecerá aqui conforme você preenche os campos acima.
                        </div>
                    </div>
                </div>
            </div>

                        <!-- Seção: Categorização -->
            <div class="bia-card">
                <div class="bia-card-header">
                    <span class="dashicons dashicons-category"></span>
                    <h3>Categorização</h3>
                </div>
                
                <div class="bia-grid">
                    <div class="bia-form-group">
                        <label for="autor_posts">
                            Autor dos Posts
                            <span class="bia-tooltip">
                                <span class="dashicons dashicons-editor-help"></span>
                                <span class="bia-tooltip-text">Selecione o autor que será atribuído aos posts gerados.</span>
                            </span>
                        </label>
                        <select id="autor_posts" name="autor_posts">
                            <?php
                            $usuarios = get_users(['role__in' => ['administrator', 'editor', 'author', 'contributor']]);
                            foreach ($usuarios as $usuario) {
                                echo '<option value="' . esc_attr($usuario->ID) . '" ' . selected($autor_posts_padrao, $usuario->ID, false) . '>' . esc_html($usuario->display_name) . '</option>';
                            }
                            ?>
                        </select>
                    </div>
                    
                    <div class="bia-form-group">
                        <label for="tags_posts">
                            Tags (opcional)
                            <span class="bia-tooltip">
                                <span class="dashicons dashicons-editor-help"></span>
                                <span class="bia-tooltip-text">Insira tags relacionadas ao conteúdo, separadas por vírgula. Deixe em branco para gerar automaticamente.</span>
                            </span>
                        </label>
                        <input type="text" id="tags_posts" name="tags_posts" value="<?php echo esc_attr($tags_posts_padrao); ?>">
                        <p class="bia-help-text">Separe as tags por vírgula. Deixe em branco para gerar automaticamente com base no conteúdo.</p>
                    </div>
                </div>
                
                <div class="bia-form-group">
                    <label for="categorias_posts">
                        Categorias
                        <span class="bia-tooltip">
                            <span class="dashicons dashicons-editor-help"></span>
                            <span class="bia-tooltip-text">Selecione uma ou mais categorias para classificar os posts gerados.</span>
                        </span>
                    </label>
                    <div class="bia-categorias-container">
                        <div class="bia-categorias-grid">
                            <?php
                            $categorias = get_categories(['hide_empty' => false]);
                            
                            // Ordenar categorias por nome
                            usort($categorias, function($a, $b) {
                                return strcasecmp($a->name, $b->name);
                            });
                            
                            foreach ($categorias as $categoria) {
                                $checked = in_array($categoria->term_id, $categorias_posts_padrao) ? 'checked' : '';
                                echo '<div class="bia-categoria-item">';
                                echo '<label>';
                                echo '<input type="checkbox" name="categorias_posts[]" value="' . esc_attr($categoria->term_id) . '" ' . $checked . '> ';
                                echo esc_html($categoria->name);
                                echo '</label>';
                                echo '</div>';
                            }
                            ?>
                        </div>
                    </div>
                    <p class="bia-help-text">Selecione uma ou mais categorias para os posts.</p>
                </div>
            </div>
            
<!-- Seção: Call to Action -->
<div class="bia-card">
    <div class="bia-card-header">
        <span class="dashicons dashicons-megaphone"></span>
        <h3>Call to Action (Opcional)</h3>
    </div>

    <div class="bia-grid">
        <div class="bia-form-group">
            <label for="cta_titulo">Título do CTA
                <span class="bia-tooltip"><span class="dashicons dashicons-editor-help"></span>
                <span class="bia-tooltip-text">Título exibido acima do botão do CTA.</span></span>
            </label>
            <input type="text" id="cta_titulo" name="cta_titulo" placeholder="Ex: Assine agora">
        </div>

        <div class="bia-form-group">
            <label for="cta_descricao">Descrição do CTA
                <span class="bia-tooltip"><span class="dashicons dashicons-editor-help"></span>
                <span class="bia-tooltip-text">Texto descritivo abaixo do título do CTA.</span></span>
            </label>
            <textarea id="cta_descricao" name="cta_descricao" rows="2" placeholder="Ex: Clique abaixo e garanta sua vaga."></textarea>
        </div>
    </div>

    <div class="bia-grid">
        <div class="bia-form-group">
            <label for="cta_botao">Texto do Botão
                <span class="bia-tooltip"><span class="dashicons dashicons-editor-help"></span>
                <span class="bia-tooltip-text">Texto do botão (ex: Saiba mais, Comprar agora, etc).</span></span>
            </label>
            <input type="text" id="cta_botao" name="cta_botao" placeholder="Ex: Saiba mais">
        </div>

        <div class="bia-form-group">
            <label for="cta_link">Link
                <span class="bia-tooltip"><span class="dashicons dashicons-editor-help"></span>
                <span class="bia-tooltip-text">Endereço do link para onde o botão irá direcionar.</span></span>
            </label>
            <input type="url" id="cta_link" name="cta_link" placeholder="https://exemplo.com/produto">
        </div>
    </div>

    <div class="bia-form-group">
        <label for="cta_imagem">URL da Imagem
            <span class="bia-tooltip"><span class="dashicons dashicons-editor-help"></span>
            <span class="bia-tooltip-text">Imagem exibida no CTA (opcional).</span></span>
        </label>
        <input type="url" id="cta_imagem" name="cta_imagem" placeholder="https://exemplo.com/imagem.jpg">
        <p class="bia-help-text">Insira a URL completa da imagem que deseja usar no CTA.</p>
    </div>

    <div class="bia-preview">
        <h4>Visualização do CTA</h4>
        <div class="bia-preview-content" id="preview-cta">
            A visualização do CTA aparecerá aqui conforme você preenche os campos acima.
        </div>
    </div>
</div>

            
            <!-- Botão de Envio -->
            <div class="bia-submit-container">
                <button type="submit" name="gerar_ideias" class="bia-submit-button">
                    <span class="dashicons dashicons-lightbulb"></span> Gerar Ideias
                </button>
            </div>
            
            <!-- Barra de Progresso -->
            <div class="bia-progress" id="progress-container">
                <p>Gerando ideias... Por favor, aguarde.</p>
                <div class="bia-progress-bar">
                    <div class="bia-progress-bar-fill" id="progress-bar-fill"></div>
                </div>
            </div>
        </form>
    </div>

    <script type="text/javascript">
        jQuery(document).ready(function($) {
            // Função para atualizar a visualização prévia
            function updatePreview() {
                var nicho = $('#nicho_blog').val() || '[Nicho]';
                var palavrasChave = $('#palavras_chave_foco').val() || '[Palavras-chave]';
                var idioma = $('#idioma_conteudo option:selected').text();
                var conceito = $('#conceito_artigo').val();
                
                var previewText = 'Gerando ideias de conteúdo sobre <strong>' + nicho + '</strong> ';
                previewText += 'com foco nas palavras-chave <strong>' + palavrasChave + '</strong> ';
                previewText += 'em <strong>' + idioma + '</strong>.';
                
                if (conceito) {
                    previewText += '<br><br>Contexto adicional: "' + conceito + '"';
                }
                
                $('#preview-content').html(previewText);
            }
            
            // Função para atualizar a visualização do CTA
            function updateCTAPreview() {
                var titulo = $('#cta_titulo').val();
                var descricao = $('#cta_descricao').val();
                var textoBotao = $('#cta_botao').val();
                var link = $('#cta_link').val();
                var imagem = $('#cta_imagem').val();

                var ctaPreview = '';

                if (titulo || descricao || textoBotao || link || imagem) {
                    ctaPreview += '<div style="border: 1px solid #ddd; padding: 15px; text-align: center; border-radius: 4px;">';

                    if (imagem) {
                        ctaPreview += '<div style="margin-bottom: 10px;"><img src="' + imagem + '" alt="Imagem CTA" style="max-width: 100%; height: auto; max-height: 100px;"></div>';
                    }

                    if (titulo) {
                        ctaPreview += '<div style="font-weight: bold; font-size: 16px; margin-bottom: 8px;">' + titulo + '</div>';
                    }

                    if (descricao) {
                        ctaPreview += '<div style="font-size: 14px; color: #555; margin-bottom: 12px;">' + descricao + '</div>';
                    }

                    if (link && textoBotao) {
                        // Usar a classe is-style-fill para aplicar a cor primária do tema
                        ctaPreview += '<div class="wp-block-button is-style-fill"><a class="wp-block-button__link" href="' + link + '" target="_blank" rel="noopener noreferrer">' + textoBotao + '</a></div>';
                    }

                    ctaPreview += '</div>';
                } else {
                    ctaPreview = 'A visualização do CTA aparecerá aqui conforme você preenche os campos acima.';
                }

                $('#preview-cta').html(ctaPreview);
            }
            
            // Atualizar visualizações quando os campos forem alterados
            $('#nicho_blog, #palavras_chave_foco, #idioma_conteudo, #conceito_artigo').on('input change', updatePreview);
            $('#cta_titulo, #cta_descricao, #cta_botao, #cta_link, #cta_imagem').on('input change', updateCTAPreview);
            
            // Inicializar visualizações
            updatePreview();
            updateCTAPreview();
            
            // Manipular envio do formulário
            $('#form_gerar_ideias').on('submit', function(e) {
                e.preventDefault();

                // Obter categorias selecionadas
                var categoriasSelecionadas = [];
                $('input[name="categorias_posts[]"]:checked').each(function() {
                    categoriasSelecionadas.push($(this).val());
                });

                var dados = {
                    action: 'gerar_sugestoes_temas',
                    nicho_blog: $('#nicho_blog').val(),
                    palavras_chave_foco: $('#palavras_chave_foco').val(),
                    quantidade: $('#quantidade').val(),
                    idioma_conteudo: $('#idioma_conteudo').val(),
                    conceito_artigo: $('#conceito_artigo').val(),
                    autor_posts: $('#autor_posts').val(),
                    categorias_posts: categoriasSelecionadas,
                    tags_posts: $('#tags_posts').val(),
                    cta_titulo: $('#cta_titulo').val(),
                    cta_descricao: $('#cta_descricao').val(),
                    cta_botao: $('#cta_botao').val(),
                    cta_link: $('#cta_link').val(),
                    cta_imagem: $('#cta_imagem').val()
                };

                // Mostrar barra de progresso
                $('#progress-container').show();
                var progressBarFill = $('#progress-bar-fill');
                var width = 0;
                var interval = setInterval(function() {
                    width += 5;
                    progressBarFill.css('width', width + '%');
                    if (width >= 100) {
                        clearInterval(interval);
                    }
                }, 500);

                // Desabilitar botão de envio
                $('.bia-submit-button').prop('disabled', true).css('opacity', '0.7');

                $.post(ajaxurl, dados, function(response) {
                    clearInterval(interval);
                    $('#progress-container').hide();
                    $('.bia-submit-button').prop('disabled', false).css('opacity', '1');

                    if (response.success) {
                        $.post(ajaxurl, {
                            action: 'salvar_opcoes_nicho_palavras',
                            nicho_blog: $('#nicho_blog').val(),
                            palavras_chave_foco: $('#palavras_chave_foco').val(),
                            idioma_conteudo: $('#idioma_conteudo').val(),
                            autor_posts: $('#autor_posts').val(),
                            categorias_posts: categoriasSelecionadas,
                            tags_posts: $('#tags_posts').val(),
                            cta_titulo: $('#cta_titulo').val(),
                            cta_descricao: $('#cta_descricao').val(),
                            cta_botao: $('#cta_botao').val(),
                            cta_link: $('#cta_link').val(),
                            cta_imagem: $('#cta_imagem').val()
                        });

                        window.location.href = '?page=bia-blog-infinito-automatico&tab=produzir_conteudos';
                    } else {
                        alert('Erro ao gerar sugestões: ' + response.data);
                    }
                }).fail(function(xhr, status, error) {
                    alert('Erro na requisição: ' + error);
                    clearInterval(interval);
                    $('#progress-container').hide();
                    $('.bia-submit-button').prop('disabled', false).css('opacity', '1');
                });
            });
        });
    </script>
    <?php
}

add_action('wp_ajax_salvar_opcoes_nicho_palavras', 'salvar_opcoes_nicho_palavras');
function salvar_opcoes_nicho_palavras() {
   if (isset($_POST['nicho_blog']) && isset($_POST['palavras_chave_foco'])) {
       update_option('nicho_blog', sanitize_text_field($_POST['nicho_blog']));
       update_option('palavras_chave_foco', sanitize_text_field($_POST['palavras_chave_foco']));
       
       // Salvar idioma selecionado
       if (isset($_POST['idioma_conteudo'])) {
           update_option('idioma_conteudo_padrao', sanitize_text_field($_POST['idioma_conteudo']));
       }
       
       // Salvar novas opções
       if (isset($_POST['autor_posts'])) {
           update_option('autor_posts_padrao', intval($_POST['autor_posts']));
       }
       
       if (isset($_POST['categorias_posts']) && is_array($_POST['categorias_posts'])) {
           $categorias = array_map('intval', $_POST['categorias_posts']);
           update_option('categorias_posts_padrao', $categorias);
       }
       
       if (isset($_POST['tags_posts'])) {
           update_option('tags_posts_padrao', sanitize_text_field($_POST['tags_posts']));
       }
       
       // Salvar opções do CTA
       if (isset($_POST['cta_titulo'])) {
           update_option('cta_titulo_padrao', sanitize_text_field($_POST['cta_titulo']));
       }
       
       if (isset($_POST['cta_descricao'])) {
           update_option('cta_descricao_padrao', sanitize_text_field($_POST['cta_descricao']));
       }
       
       if (isset($_POST['cta_botao'])) {
           update_option('cta_botao_padrao', sanitize_text_field($_POST['cta_botao']));
       }
       
       if (isset($_POST['cta_link'])) {
           update_option('cta_link_padrao', esc_url_raw($_POST['cta_link']));
       }
       
       if (isset($_POST['cta_imagem'])) {
           update_option('cta_imagem_padrao', esc_url_raw($_POST['cta_imagem']));
       }
       
       wp_send_json_success('Opções salvas com sucesso.');
   } else {
       wp_send_json_error('Erro ao salvar as opções.');
   }
}

add_action('wp_ajax_gerar_sugestoes_temas', 'gerar_sugestoes_temas');
function gerar_sugestoes_temas() {
   if (!isset($_POST['nicho_blog']) || !isset($_POST['palavras_chave_foco']) || !isset($_POST['quantidade'])) {
       wp_send_json_error('Os campos Nicho do Blog, Palavras-chave Foco e Quantidade são obrigatórios.');
       return;
   }

   $nicho_blog = sanitize_text_field($_POST['nicho_blog']);
   $palavras_chave_foco = sanitize_text_field($_POST['palavras_chave_foco']);
   $quantidade = intval($_POST['quantidade']);
   $conceito_artigo = isset($_POST['conceito_artigo']) ? sanitize_textarea_field($_POST['conceito_artigo']) : '';
   
   // Obter idioma selecionado
   $idioma_conteudo = isset($_POST['idioma_conteudo']) ? sanitize_text_field($_POST['idioma_conteudo']) : 'Portugues';
   
   // Obter novos campos
   $autor_posts = isset($_POST['autor_posts']) ? intval($_POST['autor_posts']) : 0;
   $categorias_posts = isset($_POST['categorias_posts']) && is_array($_POST['categorias_posts']) ? 
                      array_map('intval', $_POST['categorias_posts']) : [];
   $tags_posts = isset($_POST['tags_posts']) ? sanitize_text_field($_POST['tags_posts']) : '';
   
   // Obter campos do CTA
   $cta_titulo = isset($_POST['cta_titulo']) ? sanitize_text_field($_POST['cta_titulo']) : '';
   $cta_descricao = isset($_POST['cta_descricao']) ? sanitize_text_field($_POST['cta_descricao']) : '';
   $cta_botao = isset($_POST['cta_botao']) ? sanitize_text_field($_POST['cta_botao']) : '';
   $cta_link = isset($_POST['cta_link']) ? esc_url_raw($_POST['cta_link']) : '';
   $cta_imagem = isset($_POST['cta_imagem']) ? esc_url_raw($_POST['cta_imagem']) : '';

   $palavras_chave_array = array_map('trim', explode(',', $palavras_chave_foco));
   $temas_sugeridos = get_option('temas_sugeridos', array());

   $sugestoes_geradas = gerar_temas_chatgpt($nicho_blog, $palavras_chave_array, $conceito_artigo, $quantidade, $idioma_conteudo);

   if (!$sugestoes_geradas || !is_array($sugestoes_geradas)) {
       wp_send_json_error('Erro ao gerar sugestões de temas.');
       return;
   }

   foreach ($sugestoes_geradas as $sugestao) {
       $titulo_limpo = preg_replace('/^\d+\.\s*/', '', $sugestao);
       $titulo_limpo = str_replace(array('"', "'"), '', $titulo_limpo);

       if (!empty($titulo_limpo)) {
           $tema_id = uniqid('tema_');
           $tema_info = array(
               'tema' => sanitize_text_field($titulo_limpo),
               'nicho' => $nicho_blog,
               'palavras_chave' => implode(', ', $palavras_chave_array),
               'idioma' => $idioma_conteudo, // Armazenar o idioma selecionado
               'timestamp' => current_time('timestamp')
           );
           
           // Adicionar campos extras apenas se estiverem definidos
           if ($autor_posts > 0) {
               $tema_info['autor_id'] = $autor_posts;
           }
           
           if (!empty($categorias_posts)) {
               $tema_info['categorias'] = $categorias_posts;
           }
           
           if (!empty($tags_posts)) {
               $tema_info['tags'] = $tags_posts;
           }
           
           // Adicionar campos do CTA apenas se estiverem definidos
           if (!empty($cta_titulo)) {
               $tema_info['cta_titulo'] = $cta_titulo;
           }
           
           if (!empty($cta_descricao)) {
               $tema_info['cta_descricao'] = $cta_descricao;
           }
           
           if (!empty($cta_botao)) {
               $tema_info['cta_botao'] = $cta_botao;
           }
           
           if (!empty($cta_link)) {
               $tema_info['cta_link'] = $cta_link;
           }
           
           if (!empty($cta_imagem)) {
               $tema_info['cta_imagem'] = $cta_imagem;
           }
           
           $temas_sugeridos[$tema_id] = $tema_info;
       }
   }

   if (strlen(serialize($temas_sugeridos)) > 1048576) {
       error_log('Erro: Os dados são muito grandes para serem salvos.');
       wp_send_json_error('Os dados gerados são muito grandes para serem salvos.');
       return;
   }

   if (update_option('temas_sugeridos', $temas_sugeridos)) {
       wp_send_json_success('Temas gerados com sucesso.');
   } else {
       error_log('Erro ao atualizar a opção temas_sugeridos.');
       wp_send_json_error('Erro ao salvar os temas gerados.');
   }
}

function gerar_temas_chatgpt($nicho, $palavras_chave_array, $conceito, $quantidade, $idioma = 'Portugues') {
   $api_key = get_option('bia_gpt_dalle_key');  // Correção: chave correta

   if (!$api_key) {
       error_log('A chave API GPT-4/DALL·E não foi configurada corretamente.');
       return false;
   }

   $palavras_chave_texto = implode(', ', $palavras_chave_array);
   
   // Adicionar instrução de idioma ao prompt
   $instrucao_idioma = "Gere os títulos em $idioma. ";
   
   $prompt_ideias_aprimorado = "Você é um estrategista de conteúdo e especialista em SEO com profundo conhecimento do nicho de '". $nicho . "'.

Sua missão é gerar " . $quantidade . " ideias de títulos para artigos de blog, utilizando as seguintes palavras-chave como base e inspiração: '" . $palavras_chave_texto . "'.

" . $instrucao_idioma . "Os títulos gerados DEVEM seguir as seguintes diretrizes para máxima relevância, engajamento e otimização para SEO em 2025:

1.  **Relevância Profunda para o Nicho:** Cada título deve ser altamente relevante para o público de '". $nicho . "', abordando seus interesses, dores, necessidades ou curiosidades.
2.  **Otimização SEO Estratégica:**
    *   **Palavra-Chave Principal:** Incorpore a palavra-chave mais relevante de '". $palavras_chave_texto . "' ou uma variação semântica forte, preferencialmente no início do título, de forma natural.
    *   **Comprimento Ideal:** Mantenha os títulos concisos, idealmente entre 50-60 caracteres (máximo 70 caracteres), para evitar truncamento nas SERPs.
    *   **Clareza e Intenção:** O título deve comunicar claramente o tema central do artigo e alinhar-se com uma possível intenção de busca do usuário (ex: aprender algo, resolver um problema, encontrar uma lista, comparar opções).
    *   **Evitar Clickbait:** Os títulos devem ser atraentes, mas NUNCA enganosos. Devem refletir fielmente o conteúdo que o artigo entregará.

3.  **Engajamento e CTR (Click-Through Rate):**
    *   **Números e Listas:** Considere o uso de números para listas (ex: '7 Dicas Infalíveis para X em ". $nicho . "') ou dados específicos.
    *   **Perguntas (com Moderação):** Títulos em formato de pergunta podem ser eficazes se refletirem dúvidas genuínas do público. Priorize afirmações impactantes sempre que possível.
    *   **Palavras de Impacto (Power Words):** Utilize palavras que gerem curiosidade, urgência ou emoção, mas sem exageros.

4.  **Diversidade de Formatos:**
    *   **Guias e Tutoriais:** 'Como Fazer X', 'Guia Completo para Y', 'X Passos para Dominar Y'
    *   **Listas e Compilações:** 'X Maneiras de...', 'X Ferramentas Essenciais para...'
    *   **Análises e Comparações:** 'X vs Y: Qual é Melhor para...', 'Análise Completa de X'
    *   **Tendências e Novidades:** 'Tendências de X para 2025', 'O Que Esperar de X em 2025'
    *   **Problemas e Soluções:** 'Como Resolver X', 'X Soluções para o Problema Y'

5.  **Adaptação Cultural e Linguística:**
    *   Os títulos devem ressoar com o público-alvo específico, considerando nuances culturais e linguísticas do mercado em questão.
    *   Utilize terminologia familiar ao público do nicho '". $nicho . "'.

6.  **Contexto Adicional:**
" . (!empty($conceito) ? "    *   Considere o seguinte contexto/introdução ao gerar os títulos: '" . $conceito . "'" : "    *   Não há contexto adicional fornecido. Foque nas palavras-chave e no nicho.") . "

Formate sua resposta como uma lista numerada simples, com cada título em uma linha separada, sem explicações adicionais. Exemplo:
1. Título do Artigo 1
2. Título do Artigo 2
(e assim por diante)";

   $args = array(
       'headers' => array(
           'Content-Type' => 'application/json',
           'Authorization' => 'Bearer ' . $api_key,
       ),
       'body' => json_encode(array(
           'model' => 'gpt-4o-mini',
           'messages' => array(
               array('role' => 'system', 'content' => 'Você é um assistente especialista em SEO e criação de conteúdo.'),
               array('role' => 'user', 'content' => $prompt_ideias_aprimorado),
           ),
           'max_tokens' => 2000,
           'temperature' => 0.7,
       )),
       'timeout' => 60,
   );

   $response = wp_remote_post('https://api.openai.com/v1/chat/completions', $args);

   if (is_wp_error($response)) {
       error_log('Erro na requisição: ' . $response->get_error_message());
       return false;
   }

   $body = wp_remote_retrieve_body($response);
   $result = json_decode($body, true);

   if (isset($result['choices']) && !empty($result['choices'])) {
       $content = $result['choices'][0]['message']['content'];
       $lines = explode("\n", $content);
       $sugestoes = array();

       foreach ($lines as $line) {
           $line = trim($line);
           if (!empty($line) && preg_match('/^\d+\./', $line)) {
               $sugestoes[] = $line;
           }
       }

       return $sugestoes;
   }

   return false;
}