<?php
function bia_loja_da_bia() {
    ?>
    <div class="wrap bia-loja">
        <h1>Loja da BIA</h1>

        <!-- Menu de Navegação Interna -->
        <div style="text-align:center; margin-bottom:30px;">
            <a href="#planos" style="margin:0 10px; text-decoration:none; color:#8c52ff; font-weight:bold;">Planos</a> |
            <a href="#viral" style="margin:0 10px; text-decoration:none; color:#8c52ff; font-weight:bold;">Viral Fácil</a> |
            <a href="#leadcenter" style="margin:0 10px; text-decoration:none; color:#8c52ff; font-weight:bold;">LeadCenter</a>
        </div>

        <!-- Banner do Modelo de Blog Pronto com imagem -->
        <div style="margin: 30px 0; text-align: center;">
            <a href="https://bloginfinitoautomatico.com.br/checkout/?add-to-cart=3594&quantity=1" target="_blank" style="display: inline-block;">
                <img src="https://bloginfinitoautomatico.com.br/wp-content/uploads/2025/05/banner-modelo-de-blog-pronto.jpeg" 
                     alt="Modelo de Blog Pronto" 
                     style="max-width: 100%; height: auto; border-radius: 10px; box-shadow: 0 4px 12px rgba(0,0,0,0.08); border: 1px solid #e2e4e7;">
            </a>
        </div>

        <!-- Título chamativo centralizado: Turbine seu Plano -->
        <div id="planos" style="margin: 40px 0 20px 0; background: #f9f9f9; border-radius: 8px; border: 1px solid #e2e4e7; padding: 25px 20px; text-align: center; box-shadow: 0 2px 6px rgba(0,0,0,0.03);">
            <span class="dashicons dashicons-controls-forward" style="font-size: 24px; color: #8c52ff; display: inline-block; margin-bottom: 8px;"></span>
            <h2 style="margin: 0; font-size: 22px; color: #23282d;">TURBINE SEU PLANO</h2>
        </div>

        <!-- Grid de Planos -->
        <div class="planos-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap: 20px; margin-top: 40px;">
            <?php
            $planos = [
                [
                    'badge' => 'PREFERIDO',
                    'title' => '5 SITES',
                    'preco_original' => '<del>R$497</del>',
                    'preco_final' => 'R$197',
                    'link' => 'https://bloginfinitoautomatico.com.br/cart/?add-to-cart=3379&quantity=1',
                    'extras' => []
                ],
                [
                    'title' => '20 SITES',
                    'preco_original' => '<del>R$997</del>',
                    'preco_final' => 'R$497',
                    'link' => 'https://bloginfinitoautomatico.com.br/checkout/?add-to-cart=1971&quantity=1',
                    'extras' => ['Modelo de Blog Pronto (R$197)']
                ],
                [
                    'title' => 'SITES ILIMITADOS',
                    'preco_original' => '<del>R$1.997</del>',
                    'preco_final' => 'R$997',
                    'link' => 'https://bloginfinitoautomatico.com.br/checkout/?add-to-cart=1972&quantity=1',
                    'extras' => ['Modelo de Blog Pronto (R$197)', 'Sites Ilimitados']
                ]
            ];

            foreach ($planos as $plano) {
                echo '<div style="background: #fff; border-radius: 12px; padding: 30px; box-shadow: 0 6px 16px rgba(0,0,0,0.06); border: 1px solid #e2e4e7; position: relative; text-align: center; transition: 0.3s;">';
                if (!empty($plano['badge'])) {
                    echo '<div style="position: absolute; top: -12px; left: -12px; background: #8c52ff; color: #fff; font-size: 11px; padding: 6px 12px; border-radius: 6px; font-weight: bold;">' . $plano['badge'] . '</div>';
                }
                echo '<h3 style="color: #8c52ff; font-size: 24px; margin-bottom: 5px;">' . $plano['title'] . '</h3>';
                echo '<p style="text-transform: uppercase; font-size: 13px; color: #888; margin-bottom: 10px;">Licença Anual</p>';
                echo '<p style="font-size: 14px; color: #aaa; margin: 0;">' . $plano['preco_original'] . '</p>';
                echo '<p style="font-size: 28px; color: #8c52ff; font-weight: bold; margin: 0;">' . $plano['preco_final'] . '</p>';
                echo '<p style="font-size: 12px; color: #888;">TOTAL</p>';
                echo '<ul style="margin-top: 20px; text-align: left; padding-left: 0; list-style: none; font-size: 14px; color: #444;">';
                echo '<li title="Você poderá baixar o plugin imediatamente">✔ Download Imediato</li>';
                echo '<li>✔ Atualizações Por 1 Ano</li>';
                echo '<li>✔ Artigos Ilimitados</li>';
                echo '<li>✔ Use sua própria chave da Open AI</li>';
                echo '<li>✔ Suporte Whatsapp 24/7</li>';
                foreach ($plano['extras'] as $extra) {
                    echo '<li>✔ ' . $extra . '</li>';
                }
                echo '</ul>';
                echo '<a href="' . $plano['link'] . '" target="_blank" style="display: inline-block; margin-top: 20px; background-color: #8c52ff; color: #fff; padding: 12px 26px; border-radius: 6px; font-weight: bold; text-decoration: none; font-size: 15px;">QUERO ESTE PLANO</a>';
                echo '</div>';
            }
            ?>
        </div>

        <!-- Título Viral Fácil -->
        <div id="viral" style="margin: 60px 0 20px 0; background: #f9f9f9; border-radius: 8px; border: 1px solid #e2e4e7; padding: 25px 20px; text-align: center; box-shadow: 0 2px 6px rgba(0,0,0,0.03);">
            <span class="dashicons dashicons-share-alt2" style="font-size: 24px; color: #8c52ff; display: inline-block; margin-bottom: 8px;"></span>
            <h2 style="margin: 0; font-size: 20px; color: #23282d;">CRESÇA E ENGAJE SEU INSTAGRAM COM A VIRAL FÁCIL</h2>
        </div>

        <iframe src="https://viralfacil.com.br/" style="width: 100%; height: 500px; border: none; border-radius: 0 0 10px 10px;"></iframe>
        <div style="text-align: center; margin-top: 10px;">
            <a href="https://viralfacil.com.br/" target="_blank" style="display:inline-block; background:#ff5e5e; color:white; padding:12px 20px; border-radius:6px; text-decoration:none; font-weight:bold;">Contratar Agora o Viral Fácil</a>
        </div>

        <!-- Título LeadCenter -->
        <div id="leadcenter" style="margin: 60px 0 20px 0; background: #f9f9f9; border-radius: 8px; border: 1px solid #e2e4e7; padding: 25px 20px; text-align: center; box-shadow: 0 2px 6px rgba(0,0,0,0.03);">
            <span class="dashicons dashicons-format-chat" style="font-size: 24px; color: #8c52ff; display: inline-block; margin-bottom: 8px;"></span>
            <h2 style="margin: 0; font-size: 20px; color: #23282d;">ATENDA SEUS LEADS 24HRS/DIA COM O LEADCENTER</h2>
        </div>

        <iframe src="https://leadcenter.com.br/" style="width: 100%; height: 500px; border: none; border-radius: 0 0 10px 10px;"></iframe>
        <div style="text-align: center; margin-top: 10px;">
            <a href="https://app.leadcenter.com.br/signup" target="_blank" style="display:inline-block; background:#4a90e2; color:white; padding:12px 20px; border-radius:6px; text-decoration:none; font-weight:bold;">Criar Conta no LeadCenter</a>
        </div>
    </div>
    <?php
}
