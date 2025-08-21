<?php
if (!defined('WP_UNINSTALL_PLUGIN')) {
    exit;
}

delete_option('bia_gpt_dalle_key');
delete_option('bia_nome_completo');
delete_option('bia_email_compra');
delete_option('bia_whatsapp');
delete_option('bia_cpf');
delete_option('bia_data_nascimento');
delete_option('bia_licenca_bia');