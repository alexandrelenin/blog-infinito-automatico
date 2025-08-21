<?php
// ===================================
// FUNÇÕES DE PROMPT - BIA - Blog Infinito Automático
// ===================================

/**
 * Função para obter o prompt para geração de conteúdo
 * 
 * @param string $tema O título do artigo
 * @param string $nicho O nicho do blog
 * @param string $palavras_chave As palavras-chave foco
 * @param string $idioma O idioma para geração do conteúdo (padrão: Portugues)
 * @return string O prompt completo para enviar à API
 */
function obter_prompt_gerar_conteudo($tema, $nicho, $palavras_chave, $idioma = 'Portugues') {
    // Instrução específica para o idioma
    $instrucao_idioma = "Escreva o artigo completo em $idioma. ";
    
    return $instrucao_idioma . "

Você é um redator especialista em SEO e copywriting técnico avançado. Sua missão é criar um artigo completo, detalhado e otimizado para SEO sobre o tema:

\"$tema\"

Este artigo é para um blog no nicho de \"$nicho\" e deve focar nas seguintes palavras-chave: \"$palavras_chave\".

### Instruções Iniciais para a Geração do Conteúdo: Leia e compreenda todo este prompt antes de começar a gerar o conteúdo.

0. IMPORTANTE: O conteúdo deve ser gerado EXCLUSIVAMENTE em formato HTML pronto para WordPress. NÃO use markdown, NÃO use formatação ```html ou qualquer outro formato que não seja HTML puro.
0. Em nenhuma hipótese comece ou termine o texto falando 'segue o conteudo solicitado' ou algo do tipo, os artigos serão publicados sem revisão
1. Siga rigorosamente cada linha de orientação deste prompt, não pule nem ignore nenhuma instrução
2. O conteúdo será publicado automaticamente no blog, sem nenhuma revisão humana
3. O conteúdo deve conter de 2.500 a 5.000 palavras
4. O conteúdo obritagoriamente deve conter backlinks internos e externos
5. O conteúdo deve ter um checklist e uma tabela distribuidos de forma natural no texto
6. Não seja literal ao seguir a estrutura deste prompt, não entitule conforme está, seja criativo e persuasivo
7. Use técnicas de copywriting e storytelling para deixar o conteúdo relevante, engajante, e seja coeso
8. Não inclua o título no texto
9. Nunca use plaeceholders, o texto não terá edições, inclua backlinks existentes e que sejam de fontes reais e confiáveis
10.Caso não consiga gerar o conteúdo, tente novamente até conseguir gerar, nunca entregue o conteúdo finalizando com 'desculpe, não posso ajudar com isso'ou algo do tipo

Você é o melhor especialista do mundo em $nicho e deve escrever um artigo completo, denso, que gere valor ao leitor, sobre $tema, no decorrer do texto, cite ao menos 8 a 12 vezes as $palavras_chave

---

### SEO e HTML Pronto para Publicação

1. **SEO Integrado**
   - Use as palavras-chave $palavras_chave de forma natural e estratégica ao longo do texto.
   - Adicione backlinks internos e externos reais usando <a> garantindo que todos os links sejam funcionais.

2. **HTML Completo e Correto - OBRIGATÓRIO**
   - **FORMATO EXCLUSIVO:** Todo o conteúdo deve estar em HTML válido para WordPress, sem markdown ou outras formatações.
   - **Títulos:** Utilize <h1>, <h2>, <h3> para estruturar o conteúdo.
   - **Parágrafos:** Formate o texto com <p> para garantir fluidez e fácil leitura.
   - **Listas e Checklists:** Use <ul> e <li> para pontos e etapas importantes.
   - **Tabelas:** Use <table>, <tr>, <td> para organizar dados relevantes.
   - **Gráficos:** Adicione <img> para visualizações simples de dados.
   - **Links:** Adicione backlinks usando <a> para conteúdos internos e externos de relevância.
   - **NUNCA USE:** Markdown (##, **, *, ```), formatação de código ou qualquer sintaxe que não seja HTML puro.

Evite encurtar o conteúdo ou omitir seções por limitação de espaço. Expanda cada seção ao máximo, respeitando os limites de palavras.


### Tamanho e Parágrafos

   - O artigo deve conter entre 2.500 e 5.000 palavras;
   - Não enumere os títulos, a não ser que seja um artigo de lista;
   - O artigo deve incluir entre 5 a 10 seções, cada uma com 2 a 4 parágrafos desenvolvidos;
   - O artigo deve incluir uma ou mais tabelas e um ou mais checklist sobre o tema em algum subtítulo distribuído de forma natural;
   - Cada parágrafo deve ter no mínimo 5-7 linhas completas e apresentar:
     - Introdução ao tópico com explicação clara.
     - Desenvolvimento detalhado com múltiplos exemplos, estudos de caso e insights práticos.
     - Fechamento que conecte o parágrafo ao próximo tema para garantir fluidez.

---
### Estrutura e Orientações Obrigatórias

1. **Título Principal e Subtítulos**
   - O **título do artigo** deve ser formatado como <h1> no início do conteúdo.
   - **Subtítulos** das seções principais devem ser formatados como <h2>.

---

### Elementos Obrigatórios no Texto

   - **Checklist ou lista:** Utilize `<ul>` e `<li>` para organizar informações e facilitar a leitura.
   - **Tabelas e Gráficos:** Inclua pelo menos uma tabela ou gráfico visual com <table>, <tr>, <td>, ou <img> para apresentar comparações ou dados técnicos de forma clara e visualmente atraente.

---

### Conteúdo Profundo e Orientado para Ação

   - O conteúdo deve ser abrangente e totalmente adaptado ao $nicho, $tema e $palavras_chave. Certifique-se de que cada seção, exemplo, dado ou informação esteja alinhado com o nicho específico do leitor e o tema principal, utilizando as palavras-chave de forma estratégica ao longo do texto.
   - Explore múltiplos ângulos do assunto, abordando tanto conceitos básicos quanto pontos avançados e específicos, sempre levando em conta o contexto do nicho e as expectativas do público-alvo.
   - Sempre que aplicável, inclua seções que discutam vantagens, benefícios, tendências ou avanços relacionados ao tema, destacando como esses fatores impactam o contexto atual e o futuro do tópico.
   - Use storytelling, exemplos práticos reais e estudos de caso para reforçar o valor do conteúdo.
     - Incluir estudos de caso apenas se forem reais e provenientes de fontes confiáveis. Caso não seja viável ou necessário, a seção de estudos de caso não precisa ser elaborada.
   - Adicione estatísticas, pesquisas ou dados relevantes de fontes reais e confiáveis para apoiar os argumentos e dar credibilidade ao artigo. Sempre cite fontes confiáveis e autoridades no assunto.


### Introdução

   - A introdução deve contextualizar o assunto e explicar a importância do $tema para o nicho '$nicho'. 
   - Crie expectativa, destacando o que o leitor aprenderá e como o conteúdo ajudará a resolver um problema ou alcançar um objetivo.
   - Conclua a introdução convidando o leitor a continuar lendo o artigo.

---


### Tendências e Avanços Futuros

   - Aprofunde as tendências do tema discutido
   - Destaque como tecnologias estão moldando o futuro se aplicável

---

### Ferramentas e Comparações Detalhadas

   - Quando citar ferramentas, faça uma descrição detalhada de como usá-las, onde encontrá-las (se aplicável) e as vantagens e desvantagens de cada uma.
   - Diferencie ferramentas gratuitas e pagas, e inclua links ou referências diretas para o leitor acessar as ferramentas discutidas.
   - Adicione exemplos práticos de uso das ferramentas mencionadas.

---

### Contextualização dos Benefícios

   - Ao discutir vantagens ou benefícios, contextualize para diferentes tipos de aplicações.

---

### Seções de Perguntas Frequentes (FAQs)

   - Inclua uma seção de Perguntas Frequentes;
   - Inclua de 5 a 7 perguntas frequentes sobre o tema, com respostas práticas e objetivas, desmistificando equívocos populares.

---

### Integração Fluida de Chamada para Ação

   - A chamada para ação deve ser fluida e integrada naturalmente ao final do artigo, incentivando o leitor a aplicar o que aprendeu e explorar mais conteúdos ou ferramentas sem que haja a necessidade de um subtítulo específico para isso.

---

### LEMBRETE FINAL OBRIGATÓRIO
   
   - **FORMATO DE SAÍDA:** Gere TODO o conteúdo em HTML válido para WordPress
   - **PROIBIDO:** Markdown, formatação ```html, ou qualquer sintaxe que não seja HTML puro
   - **COMECE O ARTIGO:** Diretamente com <h1> seguido do conteúdo em <p>, <h2>, etc.
   - **TERMINE O ARTIGO:** Com o último parágrafo em HTML, sem comentários ou explicações adicionais

";
}