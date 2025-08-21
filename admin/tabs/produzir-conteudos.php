<?php
// ===================================
// ABA PRODUZIR CONTEÚDOS - BIA - Blog Infinito Automático
// ===================================

/**
 * Função para incluir o layout da aba "Produzir Conteúdos".
 * Certifique-se de que o arquivo 'produzir-conteudos-layout.php' existe no diretório correto.
 */
function bia_produzir_conteudos() {
    include(plugin_dir_path(__FILE__) . 'produzir-conteudos-layout.php');
}

// Incluindo o arquivo de funcionalidades
include_once(plugin_dir_path(__FILE__) . 'produzir-conteudos-funcionalidades.php');

// Incluindo o arquivo de geração de imagem
include_once(plugin_dir_path(__FILE__) . 'gerar-imagem.php');