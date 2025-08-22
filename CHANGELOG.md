# 📋 Changelog - BIA (Blog Infinito Automático)

Todas as mudanças notáveis neste projeto serão documentadas neste arquivo.

O formato é baseado em [Keep a Changelog](https://keepachangelog.com/pt-BR/1.1.0/),
e este projeto adere ao [Semantic Versioning](https://semver.org/lang/pt-BR/).

## [Não Lançado]

### Em Desenvolvimento
- Sistema de gerenciamento de prompts para IA
- Melhorias na interface de usuário
- Otimizações de performance

## [1.2.1] - 2024-08-22

### 🐛 Corrigido
- **Fatal Error WordPress**: Corrigida ordem dos includes (abas antes do dashboard)
- **Erro Calendário**: Substituída `cal_days_in_month()` por `date()` nativo para compatibilidade
- **Compatibilidade PHP 8.x**: Substituído `match()` por `switch/case` para servidores antigos
- **Navegação entre Abas**: Corrigida lógica de redirecionamento forçado para "Minha Conta"
- **Web Components Conflict**: Eliminados loops de redirecionamento causando erros TinyMCE

### 🔧 Melhorado
- **Headers WordPress**: Adicionados metadados completos do plugin + proteção ABSPATH
- **Compatibilidade**: Eliminadas dependências de extensões PHP (Calendar, Intl)
- **Lógica de Redirecionamento**: Apenas campos críticos (API key + email) bloqueiam navegação
- **Nomes de Páginas**: Consistência total em `bia-blog-infinito-automatico`
- **Code Cleanup**: Removidos `error_log()` desnecessários para produção

### 📖 Técnico
- **WordPress**: Compatível 6.8.2+
- **PHP**: Compatível 7.4+ e 8.x
- **Dependências**: Eliminadas extensões PHP externas
- **Testado**: Docker WordPress 6.8.2 + PHP 8.1

## [1.2.0] - 2024-01-21

### ✨ Adicionado
- **Aba Gerar Ideias**: Botão "Gerar Ideias" duplicado no topo da página para melhor UX
- **Aba Gerar Ideias**: Card CTA recolhível com toggle para interface mais limpa
- **Aba Produzir Conteúdos**: Botão "Editar" ao lado do "Ver Artigo" para acesso direto ao editor WordPress
- **Aba Produzir Conteúdos**: Botão "Limpar Todos" para remoção em massa de todos os temas
- **Sistema de Changelog**: Aba dedicada para visualização do histórico de mudanças do sistema
- **Estilos CSS**: Novo estilo de botão azul (.botao-azul) para consistência visual

### 🔧 Melhorado
- **Layout Responsivo**: Uso de flexbox para melhor arranjo dos botões Ver/Editar
- **Feedback Visual**: Melhor indicação de progresso durante operações (opacidade, loading)
- **Interface de Usuario**: Card CTA recolhível para reduzir poluição visual
- **JavaScript**: Funcionalidades otimizadas com melhor tratamento de erros

### 🐛 Corrigido
- **Checkbox "Gerar Imagem"**: Agora aparece desmarcado por padrão conforme solicitado
- **Responsividade**: Layout dos botões se adapta melhor a telas menores
- **Validação**: Melhor confirmação antes de ações destrutivas

### 📖 Documentação
- Adicionado `PLANO_GERENCIAMENTO_PROMPTS.md` com especificação detalhada
- Melhorada documentação inline do código JavaScript

## [1.1.0] - 2024-01-20

### ✨ Adicionado
- **Toggle para Geração de Imagem**: Opção para ativar/desativar geração de imagens por artigo
- **Controle Granular**: Usuários podem escolher quais artigos devem ter imagens geradas
- **Persistência**: Sistema salva a preferência de geração de imagem por tema

### 🔧 Melhorado
- **Interface**: Checkbox visual para controle de geração de imagem
- **UX**: Feedback imediato quando configuração é alterada
- **Performance**: Evita geração desnecessária de imagens quando desabilitada

### 🐛 Corrigido
- **AJAX**: Tratamento de erro melhorado para atualização de preferências
- **Estado**: Sincronização correta entre interface e configuração salva

## [1.0.0] - 2024-01-19

### 🎉 Lançamento Inicial
- **Sistema de Geração de Conteúdo**: Geração automática de artigos usando GPT-4
- **Geração de Imagens**: Integração com DALL-E 3 para criação de imagens
- **Geração de Ideias**: Sistema para criação de títulos e temas para artigos
- **Interface de Usuário**: Dashboard completo integrado ao WordPress Admin
- **Gerenciamento de Temas**: Sistema para organizar e gerenciar ideias geradas

### 📚 Principais Funcionalidades
- **Aba Minha Conta**: Configuração de API keys e dados pessoais
- **Aba Gerar Ideias**: Geração inteligente de ideias para artigos
- **Aba Produzir Conteúdos**: Transformação de ideias em artigos completos
- **Aba Agendamento**: Agendamento de publicação de posts
- **Aba Calendário**: Visualização em calendário dos posts agendados
- **Aba Histórico**: Histórico de conteúdos gerados
- **Aba Excluídos**: Gerenciamento de conteúdos removidos
- **Aba Loja da BIA**: Marketplace integrado
- **Aba Seja Afiliado**: Sistema de afiliados

### 🔧 Características Técnicas
- **WordPress Plugin**: Integração nativa com WordPress
- **OpenAI Integration**: GPT-4 para texto e DALL-E 3 para imagens  
- **Responsive Design**: Interface otimizada para desktop e mobile
- **AJAX Integration**: Operações assíncronas para melhor UX
- **Security**: Validação e sanitização de dados adequada
- **Licensing**: Sistema de verificação de licenças

---

## 📝 Notas de Versão

### Sobre as Versões
- **Major** (X.0.0): Mudanças incompatíveis na API ou funcionalidades principais
- **Minor** (1.X.0): Novas funcionalidades mantendo compatibilidade
- **Patch** (1.1.X): Correções de bugs e pequenas melhorias

### Legenda dos Tipos de Mudanças
- **✨ Adicionado**: Para novas funcionalidades
- **🔧 Melhorado**: Para mudanças em funcionalidades existentes
- **🐛 Corrigido**: Para correções de bugs
- **🗑️ Removido**: Para funcionalidades removidas
- **🔒 Segurança**: Para correções relacionadas à segurança
- **📖 Documentação**: Para mudanças na documentação
- **⚡ Performance**: Para melhorias de performance

---

## 🔗 Links Úteis
- [Repositório no GitHub](https://github.com/alexandrelenin/blog-infinito-automatico)
- [Documentação](https://bloginfinitoautomatico.com.br)
- [Suporte](https://bloginfinitoautomatico.com.br/suporte)

---

*Este changelog é atualizado automaticamente a cada nova versão do BIA.*