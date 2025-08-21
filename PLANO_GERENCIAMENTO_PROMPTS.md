# 📋 PLANO: Aba de Gerenciamento de Prompts - BIA

## 🎯 **OBJETIVO**
Criar uma nova aba no sistema BIA para permitir que usuários editem e gerenciem os prompts enviados para a IA, com validação obrigatória de variáveis e interface intuitiva.

## 🔍 **ANÁLISE DO SISTEMA ATUAL**

### **Prompts Identificados:**
1. **Geração de Conteúdo** (`obter_prompt_gerar_conteudo`):
   - **Arquivo**: `admin/tabs/prompt.php`
   - **Variáveis**: `$tema`, `$nicho`, `$palavras_chave`, `$idioma`
   - **Função**: Gerar artigos completos em HTML

2. **Geração de Imagem** (`obter_prompt_imagem`):
   - **Arquivo**: `admin/tabs/produzir-conteudos-funcionalidades.php`
   - **Variáveis**: `$tema`, `$palavras_chave`, `$primeiro_paragrafo`, `$idioma`
   - **Função**: Gerar imagens com DALL-E

3. **Geração de Ideias** (`$prompt_ideias_aprimorado`):
   - **Arquivo**: `admin/tabs/gerar-ideias.php`
   - **Variáveis**: `$nicho`, `$palavras_chave_texto`, `$quantidade`, `$idioma`, `$conceito`
   - **Função**: Gerar títulos/ideias de artigos

4. **Estrutura de Artigo** (massa):
   - **Arquivo**: `admin/tabs/gerar-conteudos-em-massa.php`
   - **Variáveis**: `$tema`
   - **Função**: Criar estrutura detalhada

5. **Desenvolvimento de Artigo** (massa):
   - **Arquivo**: `admin/tabs/gerar-conteudos-em-massa.php`
   - **Variáveis**: `$tema`, `$estrutura`
   - **Função**: Desenvolver artigo baseado na estrutura

## 📐 **ARQUITETURA DA SOLUÇÃO**

### **1. Estrutura da Aba**
```
📁 admin/tabs/
├── gerenciar-prompts.php          # Aba principal
├── gerenciar-prompts-layout.php   # Interface visual
├── gerenciar-prompts-funcionalidades.php # Backend/AJAX
└── prompt-templates.php           # Templates padrão
```

### **2. Banco de Dados**
- **Opção WordPress**: `bia_prompts_customizados`
- **Estrutura JSON**:
```json
{
  "gerar_conteudo": {
    "nome": "Geração de Conteúdo",
    "prompt": "Texto do prompt...",
    "variaveis_obrigatorias": ["$tema", "$nicho", "$palavras_chave", "$idioma"],
    "descricao": "Gera artigos completos...",
    "ativo": true
  },
  "gerar_imagem": {
    "nome": "Geração de Imagem",
    "prompt": "Texto do prompt...",
    "variaveis_obrigatorias": ["$tema", "$palavras_chave"],
    "descricao": "Gera imagens...",
    "ativo": true
  }
}
```

### **3. Interface do Usuário**

#### **Layout Principal:**
- **Header**: Título + botão "Restaurar Padrões"
- **Cards**: Um card por prompt com:
  - Nome do prompt
  - Descrição do que faz
  - Status (Ativo/Inativo)
  - Botão "Editar"

#### **Modal de Edição:**
- **Campos**:
  - Nome do prompt
  - Descrição
  - Área de texto grande para o prompt
  - Lista de variáveis obrigatórias
  - Toggle ativo/inativo
- **Validação**: Verificar se todas as variáveis estão presentes
- **Preview**: Mostrar como ficará o prompt com variáveis destacadas

## 🔧 **FUNCIONALIDADES DETALHADAS**

### **1. Listagem de Prompts**
- ✅ Exibir todos os prompts do sistema
- ✅ Status visual (ativo/inativo)
- ✅ Busca/filtro por nome
- ✅ Contagem de prompts ativos

### **2. Edição de Prompts**
- ✅ Modal/página de edição
- ✅ Editor de texto com syntax highlighting
- ✅ Listagem de variáveis obrigatórias
- ✅ Validação em tempo real
- ✅ Preview do prompt

### **3. Validação de Variáveis**
- ✅ Verificar se todas as variáveis obrigatórias estão presentes
- ✅ Destacar variáveis no texto
- ✅ Impedir salvamento sem variáveis
- ✅ Mensagens de erro específicas

### **4. Backup e Restauração**
- ✅ Salvar prompts originais como backup
- ✅ Botão "Restaurar Padrões"
- ✅ Confirmação antes de restaurar
- ✅ Histórico de alterações (opcional)

### **5. Sistema de Ajuda**
- ✅ Tooltip explicando cada variável
- ✅ Exemplos de uso
- ✅ Guia de boas práticas para prompts
- ✅ Link para documentação

## 🎨 **DESIGN E UX**

### **Cores e Estilo**
- **Seguir padrão BIA**: Roxo principal (#8c52ff)
- **Cards responsivos**: Design similar aos existentes
- **Ícones**: Dashicons do WordPress
- **Estados visuais**: Cores diferentes para ativo/inativo

### **Responsividade**
- **Desktop**: Layout em grid 2-3 colunas
- **Tablet**: 2 colunas
- **Mobile**: 1 coluna, cards empilhados

## 🔒 **SEGURANÇA E VALIDAÇÃO**

### **Validação Backend**
- ✅ Sanitização de inputs
- ✅ Verificação de permissões de usuário
- ✅ Validação de variáveis obrigatórias
- ✅ Escape de output HTML

### **Proteções**
- ✅ Nonce para AJAX
- ✅ Capability check (`edit_posts`)
- ✅ Validação de tamanho de prompt
- ✅ Backup automático antes de salvar

## 📁 **ESTRUTURA DE ARQUIVOS**

### **1. `gerenciar-prompts.php`**
```php
<?php
// Função principal da aba
function bia_gerenciar_prompts() {
    include 'gerenciar-prompts-layout.php';
}
include 'gerenciar-prompts-funcionalidades.php';
?>
```

### **2. `gerenciar-prompts-layout.php`**
- Interface visual completa
- Cards de prompts
- Modal de edição
- CSS inline ou externo

### **3. `gerenciar-prompts-funcionalidades.php`**
- Funções AJAX
- Validação de prompts
- Salvamento no banco
- Restauração de padrões

### **4. `prompt-templates.php`**
- Templates padrão dos prompts
- Função para resetar para padrões
- Definição de variáveis obrigatórias

## 🧪 **VALIDAÇÃO DE VARIÁVEIS**

### **Sistema de Validação**
```php
function validar_prompt($prompt, $variaveis_obrigatorias) {
    $faltando = [];
    foreach ($variaveis_obrigatorias as $variavel) {
        if (strpos($prompt, $variavel) === false) {
            $faltando[] = $variavel;
        }
    }
    return empty($faltando) ? true : $faltando;
}
```

### **Interface de Validação**
- ✅ Highlight das variáveis no texto
- ✅ Lista de variáveis faltando
- ✅ Botão salvar desabilitado se inválido
- ✅ Contador de variáveis encontradas

## 🚀 **INTEGRAÇÃO COM SISTEMA EXISTENTE**

### **Modificações Necessárias**
1. **Dashboard principal**: Adicionar nova aba
2. **Funções de prompt**: Modificar para ler do banco
3. **Fallback**: Se prompt customizado não existir, usar padrão

### **Compatibilidade**
- ✅ Manter funções existentes funcionando
- ✅ Migração suave dos prompts atuais
- ✅ Fallback para prompts padrão

## 📊 **MÉTRICAS E MONITORAMENTO**

### **Logs de Uso**
- ✅ Quando prompts são editados
- ✅ Quando prompts são restaurados
- ✅ Erros de validação
- ✅ Performance dos prompts (opcional)

### **Analytics** (Futuro)
- Taxa de sucesso dos prompts customizados
- Prompts mais editados
- Tempo médio de edição

## 🎯 **CRONOGRAMA DE IMPLEMENTAÇÃO**

### **Fase 1: Backend (2-3 horas)**
- ✅ Estrutura de dados
- ✅ Funções AJAX
- ✅ Validação de variáveis
- ✅ Sistema de backup

### **Fase 2: Frontend (2-3 horas)**
- ✅ Layout da aba
- ✅ Cards de prompts
- ✅ Modal de edição
- ✅ CSS e responsividade

### **Fase 3: Integração (1-2 horas)**
- ✅ Conectar com sistema existente
- ✅ Migração de prompts atuais
- ✅ Testes de compatibilidade

### **Fase 4: Testes (1 hora)**
- ✅ Testes de validação
- ✅ Testes de UI/UX
- ✅ Testes de segurança

## ⚠️ **RISCOS E MITTIGAÇÕES**

### **Riscos Identificados**
1. **Quebra de compatibilidade**: Prompts editados podem quebrar funcionalidades
   - **Mitigação**: Validação rigorosa + backup automático

2. **Performance**: Muitos prompts grandes podem consumir memória
   - **Mitigação**: Limite de tamanho + paginação se necessário

3. **Segurança**: Usuários podem injetar código malicioso
   - **Mitigação**: Sanitização + escape + validação

### **Plano B**
- Sistema de rollback para prompts anteriores
- Desabilitar prompt customizado se houver erro
- Fallback automático para prompts padrão

## 🎉 **RESULTADOS ESPERADOS**

### **Para o Usuário**
- ✅ Controle total sobre prompts do sistema
- ✅ Possibilidade de otimizar para seu nicho
- ✅ Interface intuitiva e fácil de usar
- ✅ Feedback visual de validação

### **Para o Sistema**
- ✅ Flexibilidade para diferentes casos de uso
- ✅ Melhor qualidade de conteúdo gerado
- ✅ Facilidade de manutenção e updates
- ✅ Base para funcionalidades futuras

---

## 🤔 **PONTOS PARA APROVAÇÃO**

1. **A estrutura de dados proposta atende às necessidades?**
2. **O sistema de validação está adequado?**
3. **A interface proposta é intuitiva?**
4. **Há alguma funcionalidade adicional necessária?**
5. **O cronograma está realista?**

---

**Status**: 📋 Aguardando aprovação para iniciar implementação
**Tempo estimado total**: 6-9 horas
**Complexidade**: Média-Alta
**Prioridade**: Alta (melhora significativa na flexibilidade do sistema)