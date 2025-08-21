<?php
// ===================================
// ABA EXCLUÍDOS - BIA - Blog Infinito Automático
// ===================================
function bia_excluidos() {
   $temas_excluidos = get_option('temas_excluidos', array());

   // Ordena os temas excluídos para mostrar os mais recentes primeiro
   usort($temas_excluidos, function($a, $b) {
       return $b['timestamp'] - $a['timestamp'];
   });

   ?>
   <div class="wrap">
       <h2>Conteúdos Excluídos</h2>

       <!-- Tabela de conteúdos excluídos em card -->
       <div style="background: #fff; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.08); padding: 0; margin-bottom: 20px; border: 1px solid #e2e4e7;">
           <div style="border-bottom: 1px solid #f0f0f0; padding: 15px 20px; display: flex; align-items: center; background: #f9f9f9; border-radius: 8px 8px 0 0;">
               <span class="dashicons dashicons-trash" style="margin-right: 10px; color: #dc3232;"></span>
               <h3 style="margin: 0; font-size: 16px; font-weight: 600; color: #23282d;">Lista de Conteúdos Excluídos</h3>
           </div>

           <table class="wp-list-table widefat fixed striped" style="border: none; margin: 0;">
               <thead>
                   <tr>
                       <th style="width: 40%; padding: 12px 15px;">Tema Excluído</th>
                       <th style="padding: 12px 15px;">Status</th>
                       <th style="padding: 12px 15px;">Data de Exclusão</th>
                       <th style="padding: 12px 15px;">Link do Artigo</th>
                       <th style="padding: 12px 15px;">Restaurar</th>
                   </tr>
               </thead>
               <tbody>
                   <?php if (!empty($temas_excluidos) && is_array($temas_excluidos)): ?>
                       <?php foreach ($temas_excluidos as $index => $tema): ?>
                           <tr>
                               <td style="padding: 15px; vertical-align: middle;"><?php echo esc_html($tema['tema']); ?></td>
                               <td style="padding: 15px; vertical-align: middle;">
                                   <?php 
                                   $status = isset($tema['status']) ? esc_html($tema['status']) : 'Excluído';
                                   $status_bg = '#f0f0f0';
                                   $status_color = '#666';
                                   $status_icon = 'dashicons-trash';

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
                                   } elseif ($status == 'Excluído') {
                                       $status_bg = '#fbeaea';
                                       $status_color = '#dc3232';
                                       $status_icon = 'dashicons-trash';
                                   }
                                   ?>
                                   <span style="display: inline-flex; align-items: center; padding: 6px 10px; border-radius: 20px; font-size: 12px; font-weight: 500; background-color: <?php echo $status_bg; ?>; color: <?php echo $status_color; ?>; border: 1px solid rgba(0,0,0,0.05);">
                                       <span class="dashicons <?php echo $status_icon; ?>" style="font-size: 14px; width: 14px; height: 14px; margin-right: 6px;"></span>
                                       <?php echo $status; ?>
                                   </span>
                               </td>
                               <td style="padding: 15px; vertical-align: middle;"><?php echo date('d/m/Y H:i', $tema['timestamp']); ?></td>
                               <td style="padding: 15px; vertical-align: middle;">
                                   <?php 
                                   if (isset($tema['link'])) {
                                       echo '<a href="' . esc_url($tema['link']) . '" target="_blank" style="display: inline-flex; align-items: center; background-color: #8c52ff; color: white; padding: 6px 12px; border-radius: 20px; text-decoration: none; font-size: 12px; font-weight: 500; transition: all 0.2s ease; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">';
                                       echo '<span class="dashicons dashicons-visibility" style="font-size: 14px; width: 14px; height: 14px; margin-right: 6px;"></span>';
                                       echo 'Ver Artigo</a>';
                                   } else {
                                       echo '<span style="color: #999;">N/A</span>';
                                   }
                                   ?>
                               </td>
                               <td style="padding: 15px; vertical-align: middle;">
                                   <button type="button" class="button restaurar-tema" data-tema="<?php echo esc_attr($tema['tema']); ?>" style="display: inline-flex; align-items: center; background-color: #46b450; color: white; padding: 6px 12px; border-radius: 20px; text-decoration: none; font-size: 12px; font-weight: 500; transition: all 0.2s ease; box-shadow: 0 1px 3px rgba(0,0,0,0.1); border: none; cursor: pointer;">
                                       <span class="dashicons dashicons-undo" style="font-size: 14px; width: 14px; height: 14px; margin-right: 6px;"></span>
                                       Restaurar
                                   </button>
                               </td>
                           </tr>
                       <?php endforeach; ?>
                   <?php else: ?>
                       <tr>
                           <td colspan="5" style="padding: 20px; text-align: center; color: #666;">
                               <span class="dashicons dashicons-info" style="font-size: 24px; width: 24px; height: 24px; margin-right: 10px; vertical-align: middle;"></span>
                               Nenhum conteúdo excluído encontrado.
                           </td>
                       </tr>
                   <?php endif; ?>
               </tbody>
           </table>
       </div>
   </div>

   <script type="text/javascript">
       jQuery(document).ready(function($) {
           $('.restaurar-tema').on('click', function() {
               var temaTexto = $(this).data('tema');

               $.post(ajaxurl, {
                   action: 'restaurar_tema_excluido',
                   tema_texto: temaTexto
               }, function(response) {
                   if (response.success) {
                       alert('Tema restaurado com sucesso!');
                       location.reload();
                   } else {
                       alert('Erro ao restaurar o tema: ' + response.data);
                   }
               });
           });
       });
   </script>
<?php
}

// Função AJAX para restaurar o tema excluído
add_action('wp_ajax_restaurar_tema_excluido', 'restaurar_tema_excluido');
function restaurar_tema_excluido() {
    $tema_texto = isset($_POST['tema_texto']) ? sanitize_text_field($_POST['tema_texto']) : null;

    if (!$tema_texto) {
        wp_send_json_error('Tema não enviado.');
        return;
    }

    $temas_excluidos = get_option('temas_excluidos', []);
    $temas_sugeridos = get_option('temas_sugeridos', []);
    $encontrado = false;

    foreach ($temas_excluidos as $i => $tema) {
        if ($tema['tema'] === $tema_texto) {
            $temas_sugeridos[] = $tema;
            unset($temas_excluidos[$i]);
            $encontrado = true;
            break;
        }
    }

    if (!$encontrado) {
        wp_send_json_error('Tema não encontrado.');
        return;
    }

    update_option('temas_excluidos', array_values($temas_excluidos));
    update_option('temas_sugeridos', $temas_sugeridos);

    wp_send_json_success('Tema restaurado com sucesso.');
}