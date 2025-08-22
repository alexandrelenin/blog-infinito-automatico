<?php
function bia_agendamento_rascunhos() {
    $rascunhos_total = count(get_posts([
        'post_status' => 'draft',
        'post_type' => 'post',
        'posts_per_page' => -1,
        'fields' => 'ids',
    ]));

    echo '<link rel="stylesheet" href="' . BIA_URL . 'assets/css/agendamento.css?v=' . time() . '" type="text/css" media="all" />';
    ?>
    <h2>Agendamento em Massa</h2>

    <style>
    #bia-agendar-form .button-primary {
        background: #8c52ff !important;
        border-color: #8c52ff !important;
        color: white !important;
        font-weight: 600;
        font-size: 15px;
        padding: 10px 30px;
        border-radius: 90px;
        box-shadow: 0 4px 10px rgba(140, 82, 255, 0.3);
        transition: all 0.3s ease;
    }

    #bia-agendar-form .button-primary:hover {
        background: #7b48e5 !important;
        border-color: #7b48e5 !important;
        color: white !important;
        transform: translateY(-2px);
        box-shadow: 0 6px 15px rgba(140, 82, 255, 0.4);
    }

    @media (max-width: 768px) {
        .wp-list-table thead {
            display: none;
        }
        .wp-list-table tr {
            display: block;
            margin-bottom: 15px;
            border: 1px solid #e2e4e7;
            border-radius: 8px;
            padding: 10px;
        }
        .wp-list-table td {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border: none;
        }
        .wp-list-table td::before {
            content: attr(data-label);
            font-weight: 600;
            color: #555;
        }
    }
    </style>

    <form method="post" id="bia-agendar-form">
        <table class="form-table">
            <tr>
                <th><label for="total">Quantidade total de posts (<?php echo $rascunhos_total; ?> produzidos):</label></th>
                <td><input type="number" name="total" min="1" max="<?php echo $rascunhos_total; ?>" required></td>
            </tr>
            <tr>
                <th><label for="quantidade">Quantidade de posts por período:</label></th>
                <td><input type="number" name="quantidade" value="1" min="1" required></td>
            </tr>
            <tr>
                <th><label for="frequencia">Frequência:</label></th>
                <td>
                    <select name="frequencia" required>
                        <option value="diaria">Diária</option>
                        <option value="semanal">Semanal</option>
                        <option value="mensal">Mensal</option>
                    </select>
                </td>
            </tr>
            <tr>
                <th><label for="horario_inicial">Horário Inicial:</label></th>
                <td>
                    <select name="horario_inicial">
                        <?php
                        for ($h = 0; $h < 24; $h++) {
                            for ($m = 0; $m < 60; $m += 15) {
                                $time = sprintf('%02d:%02d', $h, $m);
                                echo "<option value=\"$time\">$time</option>";
                            }
                        }
                        ?>
                    </select>
                </td>
            </tr>
        </table>
        <?php submit_button('Agendar', 'primary bia-action-button'); ?>
    </form>

    <div id="bia-progress-container" style="display:none; margin-top:20px;">
        <progress id="bia-progress-bar" value="0" max="100" style="width:100%;"></progress>
        <p id="bia-status">Iniciando agendamento...</p>
    </div>

    <?php
    // Listagem dos posts agendados
    $paged = isset($_GET['paged']) ? max(1, intval($_GET['paged'])) : 1;
    $posts_per_page = 100;

    $agendados = get_posts([
        'post_status' => 'future',
        'post_type' => 'post',
        'posts_per_page' => $posts_per_page,
        'paged' => $paged,
        'orderby' => 'post_date',
        'order' => 'ASC',
    ]);

    $total_agendados = wp_count_posts()->future;

    if ($agendados) {
        echo '<h3>' . intval($total_agendados) . ' Posts Agendados</h3>';
        ?>
        <div style="background: #fff; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.08); padding: 0; margin-bottom: 20px; border: 1px solid #e2e4e7;">
            <div style="border-bottom: 1px solid #f0f0f0; padding: 15px 20px; display: flex; align-items: center; background: #f9f9f9; border-radius: 8px 8px 0 0;">
                <span class="dashicons dashicons-clock" style="margin-right: 10px; color: #8c52ff;"></span>
                <h3 style="margin: 0; font-size: 16px; font-weight: 600; color: #23282d;">Lista de Posts Agendados</h3>
            </div>
            <table class="wp-list-table widefat fixed striped" style="border: none; margin: 0;">
                <thead>
                    <tr>
                        <th style="padding: 12px 15px;">Imagem</th>
                        <th style="padding: 12px 15px;">Título</th>
                        <th style="padding: 12px 15px;">Status</th>
                        <th style="padding: 12px 15px;">Data Agendada</th>
                        <th style="padding: 12px 15px;">Link</th>
                        <th style="padding: 12px 15px;">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($agendados as $post): ?>
                        <tr>
                            <td style="padding: 15px; vertical-align: middle;" data-label="Imagem">
                                <?php
                                $thumb_url = get_the_post_thumbnail_url($post->ID, 'thumbnail');
                                echo $thumb_url ? '<img src="' . esc_url($thumb_url) . '" alt="Imagem" style="width: 50px; height: auto; border-radius: 4px;">' : '<span style="color: #999;">N/A</span>';
                                ?>
                            </td>
                            <td style="padding: 15px; vertical-align: middle;" data-label="Título"><?php echo esc_html($post->post_title); ?></td>
                            <td style="padding: 15px; vertical-align: middle;" data-label="Status">
                                <span style="display: inline-flex; align-items: center; background-color: #e8f4fd; color: #8c52ff; padding: 4px 10px; border-radius: 20px; font-size: 12px; font-weight: 500;">
                                    <span class="dashicons dashicons-clock" style="margin-right: 6px;"></span>
                                    Agendado
                                </span>
                            </td>
                            <td style="padding: 15px; vertical-align: middle;" data-label="Data Agendada"><?php echo get_the_date('d/m/Y H:i', $post->ID); ?></td>
                            <td style="padding: 15px; vertical-align: middle;" data-label="Link">
                                <a href="<?php echo esc_url(get_permalink($post->ID)); ?>" target="_blank" style="display: inline-flex; align-items: center; background-color: #8c52ff; color: white; padding: 6px 12px; border-radius: 20px; text-decoration: none; font-size: 12px; font-weight: 500;">
                                    <span class="dashicons dashicons-visibility" style="margin-right: 6px;"></span>Ver Artigo
                                </a>
                            </td>
                            <td style="padding: 15px; vertical-align: middle;" data-label="Ações">
                                <a href="<?php echo esc_url(get_edit_post_link($post->ID)); ?>" target="_blank" style="display: inline-flex; align-items: center; background-color: #f0f0f0; color: #23282d; padding: 6px 12px; border-radius: 20px; text-decoration: none; font-size: 12px; font-weight: 500;">
                                    Editar
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php
        $total_pages = ceil($total_agendados / $posts_per_page);
        $base_url = remove_query_arg('paged');

        echo '<div class="tablenav"><div class="tablenav-pages">';
        if ($paged > 1) {
            echo '<a class="prev-page" href="' . esc_url(add_query_arg('paged', $paged - 1, $base_url)) . '">&laquo; Anterior</a> ';
        }
        if ($paged < $total_pages) {
            echo '<a class="next-page" href="' . esc_url(add_query_arg('paged', $paged + 1, $base_url)) . '">Próxima &raquo;</a>';
        }
        echo '</div></div>';
    } else {
        echo '<p><em>Nenhum post agendado no momento.</em></p>';
    }
}

// JavaScript para agendamento
add_action('admin_footer', function () {
    ?>
    <script>
    jQuery(document).ready(function ($) {
        $('#bia-agendar-form').on('submit', function (e) {
            e.preventDefault();

            const form = $(this);
            const total = parseInt(form.find('[name="total"]').val());
            const quantidade = parseInt(form.find('[name="quantidade"]').val());
            const frequencia = form.find('[name="frequencia"]').val();
            const horario = form.find('[name="horario_inicial"]').val();

            $('#bia-progress-container').show();
            const progress = $('#bia-progress-bar');
            const status = $('#bia-status');
            progress.val(0);

            $.post(ajaxurl, {
                action: 'bia_obter_rascunhos',
                total: total
            }, function(res) {
                if (res.success) {
                    const rascunhos_ids = res.data.rascunhos_ids;
                    let index = 0;
                    const total_rascunhos = rascunhos_ids.length;
                    
                    function agendarProximo() {
                        $.post(ajaxurl, {
                            action: 'bia_agendar_lote',
                            index: index,
                            total: total_rascunhos,
                            quantidade: quantidade,
                            frequencia: frequencia,
                            horario_inicial: horario,
                            rascunhos_ids: rascunhos_ids
                        }, function (res) {
                            if (res.success && res.data.done !== true) {
                                index = res.data.index;
                                const percent = Math.min(100, Math.round((index / total_rascunhos) * 100));
                                progress.val(percent);
                                status.text(`Agendando... ${percent}%`);
                                agendarProximo();
                            } else {
                                progress.val(100);
                                status.text('Agendamento concluído com sucesso!');
                                setTimeout(() => location.reload(), 2000);
                            }
                        });
                    }

                    agendarProximo();
                } else {
                    status.text('Erro ao obter rascunhos: ' + (res.data?.message || 'Erro desconhecido'));
                }
            });
        });
    });
    </script>
    <?php
});
// AJAX para obter rascunhos
add_action('wp_ajax_bia_obter_rascunhos', function () {
    $total = intval($_POST['total']);
    
    $rascunhos = get_posts([
        'post_status' => 'draft',
        'post_type' => 'post',
        'posts_per_page' => $total,
        'orderby' => 'date',
        'order' => 'ASC',
        'fields' => 'ids',
    ]);
    
    wp_send_json_success([
        'rascunhos_ids' => $rascunhos,
        'total' => count($rascunhos)
    ]);
});
// AJAX para agendar lote
add_action('wp_ajax_bia_agendar_lote', function () {
    $index = intval($_POST['index']);
    $total = intval($_POST['total']);
    $quantidade = intval($_POST['quantidade']);
    $frequencia = sanitize_text_field($_POST['frequencia']);
    $horario_inicial = sanitize_text_field($_POST['horario_inicial']);
    $rascunhos_ids = isset($_POST['rascunhos_ids']) ? (array)$_POST['rascunhos_ids'] : [];

    if (empty($horario_inicial)) $horario_inicial = '08:00';
    $data_atual = date('Y-m-d');
    $hora_base = strtotime($data_atual . ' ' . $horario_inicial);

    if ($hora_base < time()) {
        $hora_base = strtotime('+1 day', $hora_base);
    }

    switch($frequencia) {
        case 'mensal':
            $dias_por_periodo = 30;
            break;
        case 'semanal':
            $dias_por_periodo = 7;
            break;
        default:
            $dias_por_periodo = 1;
            break;
    }

    if ($index >= $total || $index >= count($rascunhos_ids)) {
        wp_send_json_success(['done' => true]);
        return;
    }

    $post_id = isset($rascunhos_ids[$index]) ? $rascunhos_ids[$index] : 0;
    $post = get_post($post_id);
    if (!$post || $post->post_status !== 'draft') {
        wp_send_json_success([
            'done' => false,
            'index' => $index + 1,
            'total' => $total,
            'message' => 'Post ignorado'
        ]);
        return;
    }

    $grupo = floor($index / $quantidade);
    $posicao = $index % $quantidade;
    $dias_adicionar = $grupo * $dias_por_periodo;
    $agendamento = $hora_base + ($grupo * $dias_por_periodo * DAY_IN_SECONDS) + ($posicao * 30 * 60);


    $resultado = wp_update_post([
        'ID' => $post_id,
        'post_status' => 'future',
        'post_date' => date('Y-m-d H:i:s', $agendamento),
        'post_date_gmt' => gmdate('Y-m-d H:i:s', $agendamento),
    ]);

    wp_send_json_success([
        'done' => false,
        'index' => $index + 1,
        'total' => $total,
        'post_id' => $post_id,
        'agendamento' => date('Y-m-d H:i:s', $agendamento),
        'resultado' => $resultado
    ]);
});