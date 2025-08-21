<?php
/**
 * Layout da aba "Produzir Conteúdos" - BIA
 */
?>
<h2>Produzir Conteúdos</h2>

<!-- Menu de Ações em Massa -->
<div class="bulk-actions">
    <select id="bulk-action-select">
        <option value="">Selecione uma ação</option>
        <option value="gerar_conteudos">Produzir Artigos</option>
        <option value="excluir">Excluir Selecionados</option>
        <option value="publicar">Publicar Selecionados</option>
    </select>
    <button type="submit" id="executar-acao-em-massa" class="button executar">Executar</button>
    <div id="mass-action-warning" style="display:none; color: red; margin-top: 10px;">Não saia desta página até a conclusão.</div>
</div>
<form id="form-acoes-temas" method="post">
    <table class="wp-list-table widefat fixed striped">
        <thead>
            <tr>
                <th style="width: 5%;"><input type="checkbox" id="selecionar-todos"></th>
                <th style="width: 35%;">Tema do Artigo</th>
                <th>Produzir</th>
                <th>Publicar</th>
                <th>Excluir</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php
                $temas_sugeridos = get_option('temas_sugeridos', array());
                $status_temas = get_option('status_temas', array());

                if (!empty($temas_sugeridos) && is_array($temas_sugeridos)) {
                    $temas_sugeridos = array_filter($temas_sugeridos, function($tema) {
                        return is_array($tema) && isset($tema['timestamp']);
                    });

                    uasort($temas_sugeridos, function($a, $b) {
                        return $b['timestamp'] - $a['timestamp'];
                    });

                    foreach ($temas_sugeridos as $index => $tema) {
                        if (is_array($tema) && isset($tema['tema'])) {
                            if (!empty($tema['post_id']) && is_numeric($tema['post_id'])) {
                                $post = get_post((int) $tema['post_id']);
                                if ($post instanceof WP_Post && $post->post_status !== 'draft') {
                                    continue;
                                }
                            }
            ?>
            <tr class="tema-row" data-status="<?php echo esc_attr($status_temas[$index] ?? 'Pendente'); ?>">
                <td><input type="checkbox" class="selecionar-tema" name="temas[]" value="<?php echo esc_attr($index); ?>"></td>
                <td>
                    <span id="tema-texto-<?php echo esc_attr($index); ?>"><?php echo nl2br(esc_html(trim($tema['tema']))); ?></span>
                    <button type="button" class="button editar-tema" data-tema="<?php echo esc_attr($index); ?>">
                        <span class="dashicons dashicons-edit"></span>
                    </button>
                    <input type="text" id="input-tema-<?php echo esc_attr($index); ?>" class="tema-input" value="<?php echo esc_attr($tema['tema']); ?>" style="display: none;">
                    <button type="button" class="button salvar-tema" data-tema="<?php echo esc_attr($index); ?>" style="display: none;">Salvar</button>
                </td>
                <td>
                    <?php if (in_array($status_temas[$index] ?? '', ['Produzido', 'Publicado'])) { ?>
                        <a href="<?php echo esc_url($tema['link']); ?>" target="_blank" class="botao-verde">
                            <span class="dashicons dashicons-visibility"></span> Ver Artigo
                        </a>
                    <?php } else { ?>
                        <div class="producao-controles">
                            <button type="button" class="gerar-conteudo botao-roxo" data-tema="<?php echo esc_attr($index); ?>">
                                <span class="dashicons dashicons-edit"></span> Produzir Artigo
                            </button>
                            <div class="image-generation-controls" style="margin-top: 5px;">
                                <label style="font-size: 11px; display: flex; align-items: center;">
                                    <input type="checkbox" class="gerar-imagem-toggle" 
                                           data-tema="<?php echo esc_attr($index); ?>" 
                                           <?php echo (isset($tema['gerar_imagem']) ? ($tema['gerar_imagem'] ? 'checked' : '') : 'checked'); ?>>
                                    <span style="margin-left: 4px;">Gerar imagem</span>
                                </label>
                            </div>
                        </div>
                    <?php } ?>
                    <div class="progress-bar-container" id="progress-bar-<?php echo esc_attr($index); ?>">
                        <div class="progress-bar-fill"></div>
                    </div>
                </td>
                <td>
                    <button type="button" 
                            class="publicar-tema <?php echo in_array($status_temas[$index] ?? '', ['Produzido', 'Publicado']) ? 'botao-verde' : 'botao-desabilitado tooltip-disabled'; ?>" 
                            data-tema="<?php echo esc_attr($index); ?>" 
                            <?php echo in_array($status_temas[$index] ?? '', ['Produzido', 'Publicado']) ? '' : 'disabled'; ?>
                            title="<?php echo in_array($status_temas[$index] ?? '', ['Produzido', 'Publicado']) ? '' : 'Você precisa produzir o conteúdo antes de publicar.'; ?>">
                        <span class="dashicons dashicons-yes"></span> Publicar
                    </button>
                    <div class="progress-bar-container" id="progress-bar-publicar-<?php echo esc_attr($index); ?>">
                        <div class="progress-bar-fill"></div>
                    </div>
                </td>
                <td>
                    <button type="button" class="excluir botao-vermelho" data-tema="<?php echo esc_attr($index); ?>">
                        <span class="dashicons dashicons-trash"></span> Excluir
                    </button>
                </td>
                <td id="tema-status-<?php echo esc_attr($index); ?>">
                    <?php 
                        $status = $status_temas[$index] ?? 'Pendente';
                        $status_bg = '#f0f0f0';
                        $status_color = '#666';
                        $status_icon = 'dashicons-minus';

                        if ($status == 'Publicado') {
                            $status_bg = '#e6f3f7';
                            $status_color = '#8c52ff';
                            $status_icon = 'dashicons-yes-alt';
                        } elseif ($status == 'Produzido') {
                            $status_bg = '#e6f7e6';
                            $status_color = '#2e7d32';
                            $status_icon = 'dashicons-edit';
                        } elseif ($status == 'Pendente') {
                            $status_bg = '#fff8e5';
                            $status_color = '#996500';
                            $status_icon = 'dashicons-clock';
                        }

                        echo '<span style="display: inline-flex; align-items: center; padding: 6px 10px; border-radius: 20px; font-size: 12px; font-weight: 500; background-color: ' . $status_bg . '; color: ' . $status_color . '; border: 1px solid rgba(0,0,0,0.05);">';
                        echo '<span class="dashicons ' . $status_icon . '" style="font-size: 14px; width: 14px; height: 14px; margin-right: 6px;"></span>';
                        echo esc_html($status);
                        echo '</span>';
                    ?>
                </td>
            </tr>
            <?php }}} else { ?>
                <tr><td colspan="7">Nenhum tema sugerido encontrado.</td></tr>
            <?php } ?>
        </tbody>
    </table>
</form>