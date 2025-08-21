<?php
// Função para renderizar o conteúdo da aba "Seja Afiliado"
function bia_seja_afiliado() {
    ?>
    <div class="wrap">
        <h1>Seja Afiliado</h1>
        
        <!-- Card principal -->
        <div style="background: #fff; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.08); padding: 25px; margin: 20px 0; border: 1px solid #e2e4e7;">
            <div style="display: flex; align-items: center; margin-bottom: 20px;">
                <span class="dashicons dashicons-money-alt" style="font-size: 28px; width: 28px; height: 28px; color: #8c52ff; margin-right: 15px;"></span>
                <h2 style="margin: 0; font-size: 20px; font-weight: 600; color: #23282d;">Ganhe Dinheiro indicando a BIA e outros produtos da Maisfy</h2>
            </div>
            
            <p style="font-size: 15px; line-height: 1.6; color: #555; margin-bottom: 25px;">
                Torne-se um afiliado e comece a ganhar comissões indicando produtos digitais de qualidade que ajudam empreendedores e criadores de conteúdo a alcançarem resultados extraordinários.
            </p>

            <!-- Botão personalizado com estilo moderno -->
            <div style="margin: 25px 0; text-align: center;">
                <a href="https://maisfy.com.br/convite/produto/Ld2f8531" 
                   target="_blank" 
                   style="
                       display: inline-flex;
                       align-items: center;
                       justify-content: center;
                       background-color: #8c52ff;
                       color: #ffffff;
                       font-weight: 600;
                       text-decoration: none;
                       padding: 15px 30px;
                       border-radius: 6px;
                       border: none;
                       font-size: 16px;
                       text-transform: uppercase;
                       transition: all 0.3s ease;
                       box-shadow: 0 4px 10px rgba(140, 82, 255, 0.3);
                   "
                   onmouseover="this.style.backgroundColor='#8c52ff'; this.style.transform='translateY(-2px)'; this.style.boxShadow='0 6px 15px rgba(140, 82, 255, 0.4)';"
                   onmouseout="this.style.backgroundColor='#8c52ff'; this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 10px rgba(140, 82, 255, 0.3)';"
                >
                    <span class="dashicons dashicons-money" style="font-size: 18px; width: 18px; height: 18px; margin-right: 10px;"></span>
                    Quero me Afiliar
                </a>
            </div>
            
            <!-- Benefícios em cards -->
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); grid-gap: 20px; margin: 30px 0;">
                <div style="background: #f9f9f9; border-radius: 6px; padding: 20px; border-left: 4px solid #8c52ff;">
                    <span class="dashicons dashicons-chart-line" style="font-size: 24px; width: 24px; height: 24px; color: #8c52ff; margin-bottom: 10px;"></span>
                    <h3 style="margin: 0 0 10px 0; font-size: 16px; color: #23282d;">Comissões Atrativas</h3>
                    <p style="margin: 0; font-size: 14px; color: #666;">Ganhe comissões generosas em cada venda realizada através do seu link de afiliado.</p>
                </div>
                
                <div style="background: #f9f9f9; border-radius: 6px; padding: 20px; border-left: 4px solid #8c52ff;">
                    <span class="dashicons dashicons-admin-site" style="font-size: 24px; width: 24px; height: 24px; color: #8c52ff; margin-bottom: 10px;"></span>
                    <h3 style="margin: 0 0 10px 0; font-size: 16px; color: #23282d;">Materiais Prontos</h3>
                    <p style="margin: 0; font-size: 14px; color: #666;">Acesse materiais de divulgação profissionais para impulsionar suas vendas.</p>
                </div>
                
                <div style="background: #f9f9f9; border-radius: 6px; padding: 20px; border-left: 4px solid #8c52ff;">
                    <span class="dashicons dashicons-groups" style="font-size: 24px; width: 24px; height: 24px; color: #8c52ff; margin-bottom: 10px;"></span>
                    <h3 style="margin: 0 0 10px 0; font-size: 16px; color: #23282d;">Suporte Dedicado</h3>
                    <p style="margin: 0; font-size: 14px; color: #666;">Conte com uma equipe especializada para ajudar em sua jornada como afiliado.</p>
                </div>
            </div>
        </div>

        <!-- Título para o iframe em card -->
        <div style="background: #fff; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.08); padding: 0; margin: 20px 0; border: 1px solid #e2e4e7;">
            <div style="border-bottom: 1px solid #f0f0f0; padding: 15px 20px; display: flex; align-items: center; background: #f9f9f9; border-radius: 8px 8px 0 0;">
                <span class="dashicons dashicons-store" style="margin-right: 10px; color: #8c52ff;"></span>
                <h3 style="margin: 0; font-size: 16px; font-weight: 600; color: #23282d;">Cadastre-se na Maisfy Agora Por Aqui Mesmo!</h3>
            </div>
            
            <!-- Embedar o site via iframe -->
            <iframe 
                src="https://maisfy.com.br/?partner=W49buhu4" 
                style="width: 100%; height: 600px; border: none; border-radius: 0 0 8px 8px;" 
                title="Maisfy - Sistema de Afiliados">
            </iframe>
        </div>
    </div>
    <?php
}