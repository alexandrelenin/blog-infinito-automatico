<?php
// ============================
// REGISTRAR AS OPÇÕES DO PLUGIN
// ============================
function bia_registrar_opcoes() {
    register_setting('bia_opcoes_grupo', 'bia_gpt_dalle_key'); // Chave GPT-4/DALL·E
    register_setting('bia_opcoes_grupo', 'bia_email_compra');   // E-mail de compra
}
add_action('admin_init', 'bia_registrar_opcoes');