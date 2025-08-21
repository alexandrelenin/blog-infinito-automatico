<?php
function bia_historico() {
   $temas_historico = get_option('temas_historico', array());

   usort($temas_historico, function($a, $b) {
       return strtotime($b['data']) - strtotime($a['data']);
   });
   ?>
   <div class="wrap">
       <h2>Histórico de Temas</h2>
       
       <div style="background: #fff; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.08); padding: 0; margin-bottom: 20px; border: 1px solid #e2e4e7;">
           <div style="border-bottom: 1px solid #f0f0f0; padding: 15px 20px; display: flex; align-items: center; background: #f9f9f9; border-radius: 8px 8px 0 0;">
               <span class="dashicons dashicons-list-view" style="margin-right: 10px; color: #8c52ff;"></span>
               <h3 style="margin: 0; font-size: 16px; font-weight: 600; color: #23282d;">Lista de Temas</h3>
           </div>
           
           <table class="wp-list-table widefat fixed striped" style="border: none; margin: 0;">
               <thead>
                   <tr>
                       <th style="padding: 12px 15px;">Imagem</th>
                       <th style="padding: 12px 15px;">Tema</th>
                       <th style="padding: 12px 15px;">Status</th>
                       <th style="padding: 12px 15px;">Data</th>
                       <th style="padding: 12px 15px;">Link</th>
                   </tr>
               </thead>
               <tbody>
                   <?php if (!empty($temas_historico) && is_array($temas_historico)): ?>
                       <?php foreach ($temas_historico as $tema) {
                           if (empty($tema['post_id'])) continue;

                           $status = get_post_status($tema['post_id']);
                           if (!in_array($status, ['publish', 'future'])) continue;

                           $status_label = '';
                           $status_color = '';
                           $status_icon = '';

                           if ($status === 'publish') {
                               $status_label = 'Publicado';
                               $status_color = '#46b450';
                               $status_icon = 'dashicons-yes-alt';
                           } elseif ($status === 'future') {
                               $status_label = 'Agendado';
                               $status_color = '#8c52ff';
                               $status_icon = 'dashicons-clock';
                           }
                       ?>
                           <tr>
                               <td style="padding: 15px; vertical-align: middle;">
                                   <?php
                                   $thumb_url = get_the_post_thumbnail_url($tema['post_id'], 'thumbnail');
                                   if ($thumb_url) {
                                       echo '<img src="' . esc_url($thumb_url) . '" alt="Imagem do Tema" style="width: 50px; height: auto; border-radius: 4px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">';
                                   } elseif (!empty($tema['imagem'])) {
                                       echo '<img src="' . esc_url($tema['imagem']) . '" alt="Imagem do Tema" style="width: 50px; height: auto; border-radius: 20px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">';
                                   } else {
                                       echo '<span style="color: #999;">N/A</span>';
                                   }
                                   ?>
                               </td>
                               <td style="padding: 15px; vertical-align: middle;"><?php echo esc_html($tema['tema']); ?></td>
                               <td style="padding: 15px; vertical-align: middle;">
                                   <span style="display: inline-flex; align-items: center; background-color: <?php echo $status_color; ?>1A; color: <?php echo $status_color; ?>; padding: 4px 10px; border-radius: 20px; font-size: 12px; font-weight: 500;">
                                       <span class="dashicons <?php echo $status_icon; ?>" style="margin-right: 6px;"></span>
                                       <?php echo $status_label; ?>
                                   </span>
                               </td>
                               <td style="padding: 15px; vertical-align: middle;"><?php echo get_the_date('d/m/Y H:i', $tema['post_id']); ?></td>
                               <td style="padding: 15px; vertical-align: middle;">
                                   <?php 
                                   if (!empty($tema['link'])) {
                                       echo '<a href="' . esc_url($tema['link']) . '" target="_blank" style="display: inline-flex; align-items: center; background-color: #8c52ff; color: white; padding: 6px 12px; border-radius: 20px; text-decoration: none; font-size: 12px; font-weight: 500; transition: all 0.2s ease; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">';
                                       echo '<span class="dashicons dashicons-visibility" style="font-size: 14px; width: 14px; height: 14px; margin-right: 6px;"></span>';
                                       echo 'Ver Artigo</a>';
                                   } else {
                                       echo '<span style="color: #999;">N/A</span>';
                                   }
                                   ?>
                               </td>
                           </tr>
                       <?php } ?>
                   <?php else: ?>
                       <tr>
                           <td colspan="5" style="padding: 20px; text-align: center; color: #666;">
                               <span class="dashicons dashicons-info" style="font-size: 24px; width: 24px; height: 24px; margin-right: 10px; vertical-align: middle;"></span>
                               Nenhum tema no histórico.
                           </td>
                       </tr>
                   <?php endif; ?>
               </tbody>
           </table>
       </div>
   </div>
   <?php
}