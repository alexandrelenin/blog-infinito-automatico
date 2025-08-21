<?php
// Função para exibir a aba "Minha Conta" com layout moderno e tom roxo
function bia_minha_conta() {

    // Processar o formulário quando enviado
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['bia_gpt_dalle_key'])) {
            $chave_input = sanitize_text_field($_POST['bia_gpt_dalle_key']);
            update_option('bia_gpt_dalle_key', $chave_input);
        }

        if (isset($_POST['bia_nome_completo'])) {
            $nome_completo = sanitize_text_field($_POST['bia_nome_completo']);
            update_option('bia_nome_completo', $nome_completo);
        }

        if (isset($_POST['bia_email_compra'])) {
            $email_compra = sanitize_email($_POST['bia_email_compra']);
            update_option('bia_email_compra', $email_compra);
        }

        if (isset($_POST['bia_whatsapp'])) {
            $whatsapp = sanitize_text_field($_POST['bia_whatsapp']);
            update_option('bia_whatsapp', $whatsapp);
        }

        if (isset($_POST['bia_cpf'])) {
            $cpf = sanitize_text_field($_POST['bia_cpf']);
            update_option('bia_cpf', $cpf);
        }

        if (isset($_POST['bia_data_nascimento'])) {
            $data_nascimento = sanitize_text_field($_POST['bia_data_nascimento']);
            update_option('bia_data_nascimento', $data_nascimento);
        }

        // Geração da chave de licença e envio de e-mail se for nova
        $licenca_bia = get_option('bia_licenca_bia', '');
        if (empty($licenca_bia)) {
            $licenca_bia = bin2hex(random_bytes(16));
            update_option('bia_licenca_bia', $licenca_bia);
        // Envio do e-mail para notificação de dados salvos
        $site_url = home_url();
        $admin_email = get_option('admin_email');

        $mensagem = "🔐 Registro/Atualização do Plugin BIA:\n\n" .
                    "🔸 Nome: $nome_completo\n" .
                    "🔸 E-mail de Compra: $email_compra\n" .
                    "🔸 WhatsApp: $whatsapp\n" .
                    "🔸 CPF: $cpf\n" .
                    "🔸 Nascimento: $data_nascimento\n" .
                    "🔸 Licença: $licenca_bia\n" .
                    "🔸 Site: $site_url\n" .
                    "🔸 E-mail Admin: $admin_email\n";

        wp_mail(
            'cadastro@bloginfinitoautomatico.com.br',
            '📩 Registro ou Atualização de Dados - ' . $site_url,
            $mensagem
        );


            $site_url = home_url();
            $admin_email = get_option('admin_email');

            $mensagem = "🔐 Novo Registro do Plugin BIA:\n\n" .
                        "🔸 Nome: $nome_completo\n" .
                        "🔸 E-mail de Compra: $email_compra\n" .
                        "🔸 WhatsApp: $whatsapp\n" .
                        "🔸 CPF: $cpf\n" .
                        "🔸 Nascimento: $data_nascimento\n" .
                        "🔸 Licença: $licenca_bia\n" .
                        "🔸 Site: $site_url\n" .
                        "🔸 E-mail Admin: $admin_email\n";

            wp_mail(
                'cadastro@bloginfinitoautomatico.com.br',
                '🆕 Novo Registro BIA - ' . $site_url,
                $mensagem
            );
        }

        // Chamada ao endpoint de validação
        $response = wp_remote_post('https://bloginfinitoautomatico.com.br/wp-json/bia/v1/verificar', [
            'method'  => 'POST',
            'headers' => ['Content-Type' => 'application/json'],
            'body'    => json_encode([
                'email'   => $email_compra,
                'licenca' => $licenca_bia,
                'dominio' => home_url(),
            ]),
        ]);

        // Log opcional para debug
        if (is_wp_error($response)) {
            error_log('Erro na requisição de verificação de licença: ' . $response->get_error_message());
        } else {
            error_log('Resposta da verificação: ' . wp_remote_retrieve_body($response));
        }

        // Mostrar notificação de sucesso
        echo '<div class="bia-notice bia-notice-success">
                <div class="bia-notice-icon"><span class="dashicons dashicons-yes-alt"></span></div>
                <div class="bia-notice-content">Informações salvas com sucesso!</div>
              </div>';
    }
    
    // Obter valores salvos
    $chave = get_option('bia_gpt_dalle_key', '');
    $nome_completo = get_option('bia_nome_completo', '');
    $email_compra = get_option('bia_email_compra', '');
    $whatsapp = get_option('bia_whatsapp', '');
    $cpf = get_option('bia_cpf', '');
    $data_nascimento = get_option('bia_data_nascimento', '');
    $licenca_bia = get_option('bia_licenca_bia', '');

    // Função para ocultar parcialmente valores sensíveis
    function ocultar_parcialmente($valor) {
        return preg_replace('/(^.{8}).+(.{2})$/', '$1##########$2', $valor);
    }

    $chave_oculta = $chave ? ocultar_parcialmente($chave) : '';
    // Estilos CSS para o layout moderno com tom roxo
    ?>
    <style>
        .bia-input-saved {
            background-color: #f0f0f0 !important;
        }

        .bia-container {
            max-width: 100%;
            margin: 20px 0;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Oxygen-Sans, Ubuntu, Cantarell, "Helvetica Neue", sans-serif;
        }

        .bia-card {
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            padding: 20px;
            margin-bottom: 20px;
            border: 1px solid #e2e4e7;
            transition: box-shadow 0.3s ease;
        }

        .bia-card:hover {
            box-shadow: 0 4px 12px rgba(0,0,0,0.12);
        }

        .bia-card-header {
            border-bottom: 1px solid #f0f0f0;
            margin: -20px -20px 20px;
            padding: 15px 20px;
            display: flex;
            align-items: center;
            background: #f9f9f9;
            border-radius: 8px 8px 0 0;
        }

        .bia-card-header h3 {
            margin: 0;
            font-size: 16px;
            font-weight: 600;
            color: #23282d;
        }

        .bia-card-header .dashicons {
            margin-right: 10px;
            color: #8c52ff;
        }

        .bia-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            grid-gap: 20px;
        }

        .bia-form-group {
            margin-bottom: 15px;
        }

        .bia-form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: 500;
            color: #23282d;
        }

        .bia-form-group input[type="text"],
        .bia-form-group input[type="email"],
        .bia-form-group input[type="date"],
        .bia-form-group input[type="password"],
        .bia-form-group select,
        .bia-form-group textarea {
            width: 100%;
            padding: 8px 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-shadow: inset 0 1px 2px rgba(0,0,0,0.07);
            transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out, background-color 0.15s ease-in-out;
        }

        .bia-form-group input:focus,
        .bia-form-group select:focus,
        .bia-form-group textarea:focus {
            border-color: #8c52ff;
            box-shadow: 0 0 0 1px #8c52ff;
            outline: 2px solid transparent;
        }

        .bia-form-group input[readonly] {
            background-color: #f0f0f0;
            cursor: not-allowed;
        }

        .bia-help-text {
            font-size: 12px;
            color: #666;
            margin-top: 5px;
        }

        .bia-tooltip {
            position: relative;
            display: inline-block;
            margin-left: 5px;
            color: #8c52ff;
            cursor: help;
        }

        .bia-tooltip .dashicons {
            font-size: 16px;
            width: 16px;
            height: 16px;
        }

        .bia-tooltip .bia-tooltip-text {
            visibility: hidden;
            width: 200px;
            background-color: #23282d;
            color: #fff;
            text-align: center;
            border-radius: 4px;
            padding: 8px;
            position: absolute;
            z-index: 1;
            bottom: 125%;
            left: 50%;
            margin-left: -100px;
            opacity: 0;
            transition: opacity 0.3s;
            font-size: 12px;
            line-height: 1.4;
        }

        .bia-tooltip .bia-tooltip-text::after {
            content: "";
            position: absolute;
            top: 100%;
            left: 50%;
            margin-left: -5px;
            border-width: 5px;
            border-style: solid;
            border-color: #23282d transparent transparent transparent;
        }

        .bia-tooltip:hover .bia-tooltip-text {
            visibility: visible;
            opacity: 1;
        }
        .bia-submit-container {
            margin-top: 20px;
            text-align: right;
        }

        .bia-submit-button {
            background: #8c52ff;
            border-color: #8c52ff;
            color: #fff;
            padding: 10px 20px;
            font-size: 14px;
            font-weight: 500;
            border-radius: 20px;
            cursor: pointer;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            border: none;
            box-shadow: 0 4px 10px rgba(140, 82, 255, 0.3);
        }

        .bia-submit-button:hover {
            background: #7a42e8;
            transform: translateY(-2px);
            box-shadow: 0 6px 15px rgba(140, 82, 255, 0.4);
        }

        .bia-submit-button .dashicons {
            margin-right: 5px;
        }

        .bia-notice {
            display: flex;
            align-items: center;
            padding: 12px 15px;
            border-radius: 4px;
            margin: 15px 0;
            border-left: 4px solid;
        }

        .bia-notice-success {
            background-color: #f0f6e4;
            border-left-color: #7ad03a;
        }

        .bia-notice-icon {
            margin-right: 10px;
        }

        .bia-notice-icon .dashicons {
            font-size: 20px;
            width: 20px;
            height: 20px;
        }

        .bia-notice-success .dashicons {
            color: #7ad03a;
        }

        .bia-notice-content {
            flex: 1;
        }

        .bia-status-indicator {
            display: inline-flex;
            align-items: center;
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 500;
            margin-left: 10px;
        }

        .bia-status-active {
            background-color: #e6f7e6;
            color: #2e7d32;
        }

        .bia-status-inactive {
            background-color: #fbeaea;
            color: #c62828;
        }

        .bia-status-indicator .dashicons {
            font-size: 14px;
            width: 14px;
            height: 14px;
            margin-right: 4px;
        }

        .bia-copy-button {
            background: #f0f0f0;
            border: 1px solid #ddd;
            border-radius: 4px;
            padding: 4px 8px;
            font-size: 12px;
            cursor: pointer;
            margin-left: 10px;
            transition: background 0.2s ease;
        }

        .bia-copy-button:hover {
            background: #e0e0e0;
        }

        .bia-copy-button .dashicons {
            font-size: 14px;
            width: 14px;
            height: 14px;
            margin-right: 4px;
        }

        @media (max-width: 782px) {
            .bia-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>

    <div class="wrap bia-container">
        <h2>Minha Conta</h2>
        <form method="post" id="form_minha_conta">
            <!-- Conteúdo do formulário segue na Parte 4 -->
            <!-- Seção: Informações Pessoais -->
            <div class="bia-card">
                <div class="bia-card-header">
                    <span class="dashicons dashicons-admin-users"></span>
                    <h3>Informações Pessoais</h3>
                </div>
                <div class="bia-grid">
                    <div class="bia-form-group">
                        <label for="bia_nome_completo">Nome Completo</label>
                        <input type="text" id="bia_nome_completo" name="bia_nome_completo" value="<?php echo esc_attr($nome_completo); ?>" class="bia-input-field" />
                    </div>
                    <div class="bia-form-group">
                        <label for="bia_email_compra">E-mail de Compra</label>
                        <input type="email" id="bia_email_compra" name="bia_email_compra" value="<?php echo esc_attr($email_compra); ?>" class="bia-input-field" />
                    </div>
                </div>
                <div class="bia-grid">
                    <div class="bia-form-group">
                        <label for="bia_whatsapp">WhatsApp</label>
                        <input type="text" id="bia_whatsapp" name="bia_whatsapp" value="<?php echo esc_attr($whatsapp); ?>" class="bia-input-field" />
                    </div>
                    <div class="bia-form-group">
                        <label for="bia_cpf">CPF</label>
                        <input type="text" id="bia_cpf" name="bia_cpf" value="<?php echo esc_attr($cpf); ?>" class="bia-input-field" />
                    </div>
                </div>
                <div class="bia-form-group">
                    <label for="bia_data_nascimento">Data de Nascimento</label>
                    <input type="text" id="bia_data_nascimento" name="bia_data_nascimento" value="<?php echo esc_attr($data_nascimento); ?>" class="bia-input-field" />
                </div>
                <div class="bia-form-group">
                    <label for="bia_licenca">
                        Chave de Licença BIA
                        <?php if (!empty($licenca_bia)): ?>
                            <span class="bia-status-indicator bia-status-active">
                                <span class="dashicons dashicons-yes-alt"></span> Ativa
                            </span>
                        <?php else: ?>
                            <span class="bia-status-indicator bia-status-inactive">
                                <span class="dashicons dashicons-no-alt"></span> Inativa
                            </span>
                        <?php endif; ?>
                    </label>
                    <div style="display: flex; align-items: center;">
                        <input type="text" id="bia_licenca" value="<?php echo esc_attr($licenca_bia); ?>" readonly class="bia-input-field" style="flex: 1;" />
                        <?php if (!empty($licenca_bia)): ?>
                            <button type="button" class="bia-copy-button" onclick="copiarLicenca()">
                                <span class="dashicons dashicons-clipboard"></span> Copiar
                            </button>
                        <?php endif; ?>
                    </div>
                    <p class="bia-help-text">Esta chave é gerada automaticamente ao salvar suas informações.</p>
                </div>
            </div>

            <!-- Seção: Chaves de API -->
            <div class="bia-card">
                <div class="bia-card-header">
                    <span class="dashicons dashicons-admin-network"></span>
                    <h3>Chaves de API</h3>
                </div>
                <div class="bia-form-group">
                    <label for="bia_gpt_dalle_key">Chave da API OpenAI (GPT/DALL-E)</label>
                    <input type="password" id="bia_gpt_dalle_key" name="bia_gpt_dalle_key" value="<?php echo esc_attr($chave); ?>" class="bia-input-field" placeholder="sk-..." />
                    <p class="bia-help-text">Insira sua chave de API da OpenAI para usar GPT e DALL-E.</p>
                </div>
            </div>

            <!-- Seção: Informações do Site -->
            <div class="bia-card">
                <div class="bia-card-header">
                    <span class="dashicons dashicons-admin-site"></span>
                    <h3>Informações do Site</h3>
                </div>
                <div class="bia-grid">
                    <div class="bia-form-group">
                        <label for="bia_site_url">URL do Site</label>
                        <input type="text" id="bia_site_url" value="<?php echo esc_url(home_url()); ?>" readonly class="bia-input-field" />
                    </div>
                    <div class="bia-form-group">
                        <label for="bia_admin_email">E-mail do Administrador</label>
                        <input type="email" id="bia_admin_email" value="<?php echo esc_attr(get_option('admin_email')); ?>" readonly class="bia-input-field" />
                    </div>
                </div>
            </div>

            <!-- Botão de envio -->
            <div class="bia-submit-container">
                <button type="submit" class="bia-submit-button">
                    <span class="dashicons dashicons-saved"></span> Salvar Informações
                </button>
            </div>
        </form>
    </div>

    <!-- Scripts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const campos = document.querySelectorAll('.bia-input-field');
            campos.forEach(campo => {
                if (campo.value.trim() !== '' && !campo.readOnly) {
                    campo.classList.add('bia-input-saved');
                }
            });
        });

        function copiarLicenca() {
            const licencaInput = document.getElementById('bia_licenca');
            licencaInput.select();
            document.execCommand('copy');
            alert('Chave de licença copiada!');
        }

        jQuery(function ($) {
            $('#bia_cpf').mask('000.000.000-00');
            $('#bia_whatsapp').mask('(00) 00000-0000');
            $('#bia_data_nascimento').mask('00/00/0000');
        });
    </script>
<?php
}