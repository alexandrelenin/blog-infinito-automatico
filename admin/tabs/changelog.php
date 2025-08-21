<?php
/**
 * Aba Changelog - BIA - Blog Infinito Automático
 * 
 * Exibe o changelog do projeto de forma visual e organizada
 */

function bia_changelog() {
    // Caminho do arquivo CHANGELOG.md
    $changelog_path = plugin_dir_path(__FILE__) . '../../CHANGELOG.md';
    
    // Verificar se o arquivo existe
    if (!file_exists($changelog_path)) {
        echo '<div class="notice notice-error"><p>Arquivo de changelog não encontrado.</p></div>';
        return;
    }
    
    // Ler o conteúdo do changelog
    $changelog_content = file_get_contents($changelog_path);
    
    if (empty($changelog_content)) {
        echo '<div class="notice notice-warning"><p>Changelog vazio ou não pôde ser lido.</p></div>';
        return;
    }
    
    ?>
    
    <div class="bia-changelog-container">
        <div class="bia-changelog-header">
            <h2>
                <span class="dashicons dashicons-clipboard" style="margin-right: 10px; color: #8c52ff;"></span>
                Changelog do BIA
                <button class="bia-changelog-refresh" onclick="location.reload();">
                    <span class="dashicons dashicons-update"></span>Atualizar
                </button>
            </h2>
            <p>Histórico de todas as mudanças e melhorias do Blog Infinito Automático</p>
        </div>
        
        <div class="bia-changelog-updated">
            <span class="dashicons dashicons-info"></span>
            <strong>Última atualização:</strong> <?php echo date('d/m/Y H:i', filemtime($changelog_path)); ?>
        </div>
        
        <div class="bia-changelog-content">
            <?php
            // Converter Markdown para HTML básico
            echo bia_parse_markdown_to_html($changelog_content);
            ?>
        </div>
    </div>
    
    <script>
        jQuery(document).ready(function($) {
            // Scroll suave para âncoras
            $('a[href^="#"]').on('click', function(event) {
                var target = $(this.getAttribute('href'));
                if (target.length) {
                    event.preventDefault();
                    $('html, body').stop().animate({
                        scrollTop: target.offset().top - 100
                    }, 500);
                }
            });
            
            // Auto-refresh a cada 5 minutos (opcional)
            // setInterval(function() {
            //     location.reload();
            // }, 300000); // 5 minutos
        });
    </script>
    
    <?php
}

/**
 * Função para converter Markdown básico para HTML
 * 
 * @param string $markdown Conteúdo em Markdown
 * @return string HTML convertido
 */
function bia_parse_markdown_to_html($markdown) {
    // Converter títulos
    $html = preg_replace('/^### (.+)$/m', '<h3>$1</h3>', $markdown);
    $html = preg_replace('/^## (.+)$/m', '<h2>$1</h2>', $html);
    $html = preg_replace('/^# (.+)$/m', '<h1>$1</h1>', $html);
    
    // Converter links
    $html = preg_replace('/\[([^\]]+)\]\(([^)]+)\)/', '<a href="$2" target="_blank">$1</a>', $html);
    
    // Converter negrito
    $html = preg_replace('/\*\*(.+?)\*\*/', '<strong>$1</strong>', $html);
    
    // Converter itálico
    $html = preg_replace('/\*(.+?)\*/', '<em>$1</em>', $html);
    
    // Converter código inline
    $html = preg_replace('/`(.+?)`/', '<code>$1</code>', $html);
    
    // Converter listas
    $html = preg_replace('/^- (.+)$/m', '<li>$1</li>', $html);
    
    // Envolver listas em <ul>
    $html = preg_replace('/(<li>.*<\/li>)/s', '<ul>$1</ul>', $html);
    
    // Converter quebras de linha duplas em parágrafos
    $html = preg_replace('/\n\n/', '</p><p>', $html);
    $html = '<p>' . $html . '</p>';
    
    // Limpar parágrafos vazios
    $html = preg_replace('/<p><\/p>/', '', $html);
    $html = preg_replace('/<p>(<h[1-6]>)/', '$1', $html);
    $html = preg_replace('/(<\/h[1-6]>)<\/p>/', '$1', $html);
    $html = preg_replace('/<p>(<ul>)/', '$1', $html);
    $html = preg_replace('/(<\/ul>)<\/p>/', '$1', $html);
    
    // Converter quebras de linha simples em <br>
    $html = nl2br($html);
    
    return $html;
}
?>