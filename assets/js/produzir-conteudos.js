jQuery(document).ready(function ($) {
    // Função para editar tema
    $('.editar-tema').on('click', function () {
        var temaIndex = $(this).data('tema');
        $('#tema-texto-' + temaIndex).hide();
        $(this).hide();
        $('#input-tema-' + temaIndex).show();
        $('.salvar-tema[data-tema="' + temaIndex + '"]').show();
    });

    // Função para salvar tema editado
    $('.salvar-tema').on('click', function () {
        var temaIndex = $(this).data('tema');
        var novoTema = $('#input-tema-' + temaIndex).val();

        $.ajax({
            url: ajaxurl,
            type: 'POST',
            data: {
                action: 'atualizar_tema',
                tema_index: temaIndex,
                novo_tema: novoTema
            },
            success: function (response) {
                if (response.success) {
                    $('#tema-texto-' + temaIndex).text(novoTema).show();
                    $('#input-tema-' + temaIndex).hide();
                    $('.salvar-tema[data-tema="' + temaIndex + '"]').hide();
                    $('.editar-tema[data-tema="' + temaIndex + '"]').show();
                } else {
                    alert('Erro ao atualizar o tema: ' + response.data);
                }
            },
            error: function (xhr, status, error) {
                console.log('Erro AJAX: ', error);
                alert('Erro na comunicação com o servidor.');
            }
        });
    });

    function isConteudoProduzido(temaIndex) {
        var status = $('#tema-status-' + temaIndex).text().trim();
        return status === 'Produzido' || status === 'Publicado';
    }

    $('#selecionar-todos').on('change', function () {
        $('.selecionar-tema').prop('checked', this.checked);
    });

    $('.gerar-conteudo').on('click', function () {
        var temaIndex = $(this).data('tema');
        if (isConteudoProduzido(temaIndex)) {
            alert('Este conteúdo já foi gerado.');
            return;
        }

        gerarConteudo([temaIndex]);
    });

    $('.publicar-tema').on('click', function () {
        const botao = $(this);
        const temaIndex = botao.data('tema');

        botao.prop('disabled', true).text('Publicando...');
        $('#progress-bar-publicar-' + temaIndex).show();

        $.post(ajaxurl, {
            action: 'publicar_tema',
            tema_index: temaIndex
        }).done(function (response) {
            $('#progress-bar-publicar-' + temaIndex).hide();

            if (response.success) {
                $('#tema-status-' + temaIndex).text('Publicado');
                botao.removeClass('botao-desabilitado').addClass('botao-verde').text('Publicado');

                $('tr[data-status]').filter(function () {
                    return $(this).find('.publicar-tema').data('tema') == temaIndex;
                }).fadeOut(300, function () {
                    $(this).remove();
                });
            } else {
                botao.prop('disabled', false).text('Publicar');
                alert('Erro ao publicar: ' + response.data);
            }
        }).fail(function () {
            $('#progress-bar-publicar-' + temaIndex).hide();
            botao.prop('disabled', false).text('Publicar');
            alert('Erro de comunicação com o servidor.');
        });
    });

    $('.excluir').on('click', function () {
        var temaIndex = $(this).data('tema');
        if (confirm('Tem certeza que deseja excluir este tema?')) {
            $.post(ajaxurl, {
                action: 'excluir_tema',
                tema_index: temaIndex
            }, function (response) {
                if (response.success) {
                    alert('Tema excluído com sucesso!');
                    location.reload();
                } else {
                    alert('Erro ao excluir o tema: ' + response.data);
                }
            });
        }
    });

    $('#executar-acao-em-massa').on('click', function () {
        var acao = $('#bulk-action-select').val();
        var temasSelecionados = $('.selecionar-tema:checked').map(function () {
            return $(this).val();
        }).get();

        if (temasSelecionados.length === 0) {
            alert('Por favor, selecione pelo menos um tema.');
            return;
        }

        $('#mass-action-warning').show();

        if (acao === 'gerar_conteudos') {
            if (!confirm(`Tem certeza que deseja produzir ${temasSelecionados.length} conteúdo(s)?`)) {
                return;
            }
            gerarConteudo(temasSelecionados);
        } else if (acao === 'publicar') {
            publicarConteudo(temasSelecionados);
        } else if (acao === 'excluir') {
            if (confirm('Tem certeza que deseja excluir os temas selecionados?')) {
                $.post(ajaxurl, {
                    action: 'excluir_temas_em_massa',
                    temas: temasSelecionados
                }, function (response) {
                    if (response.success) {
                        alert('Temas excluídos com sucesso!');
                        location.reload();
                    } else {
                        alert('Erro ao excluir os temas: ' + response.data);
                    }
                });
            }
        }
    });

    async function gerarConteudo(indices) {
        for (const temaIndex of indices) {
            $('#tema-status-' + temaIndex).html(`
    <span class="bia-status-badge bia-status-produzindo pulse">
        <span class="dashicons dashicons-update"></span> Produzindo<span class="dotting">...</span>
    </span>
`);
            $('#progress-bar-' + temaIndex).show();
            var progressBar = $('#progress-bar-' + temaIndex + ' .progress-bar-fill');

            var width = 0;
            var interval = setInterval(() => {
                if (width >= 100) clearInterval(interval);
                else {
                    width += 10;
                    progressBar.css('width', width + '%');
                }
            }, 1000);

            try {
                const response = await $.post(ajaxurl, {
                    action: 'gerar_conteudo',
                    tema_index: temaIndex
                });

                clearInterval(interval);
                $('#progress-bar-' + temaIndex).hide();

                if (response.success) {
                    const link = response.data.link;
                    const postId = response.data.post_id;
                    const editLink = `/wp-admin/post.php?post=${postId}&action=edit`;
                    
                    $('#tema-status-' + temaIndex).html(`
    <span class="bia-status-badge bia-status-produzido">
        <span class="dashicons dashicons-yes"></span> Produzido
    </span>
`);
                    $('.gerar-conteudo[data-tema="' + temaIndex + '"]').replaceWith(`
    <div style="display: flex; gap: 5px; flex-wrap: wrap;">
        <a href="${link}" target="_blank" class="botao-verde">
            <span class="dashicons dashicons-visibility"></span> Ver Artigo
        </a>
        <a href="${editLink}" target="_blank" class="botao-azul">
            <span class="dashicons dashicons-edit"></span> Editar
        </a>
    </div>
`);

                    $('.publicar-tema[data-tema="' + temaIndex + '"]').removeClass('botao-desabilitado').addClass('botao-verde').prop('disabled', false);
                } else {
                    $('#tema-status-' + temaIndex).text('Erro');
                }
            } catch (e) {
                clearInterval(interval);
                $('#progress-bar-' + temaIndex).hide();
                $('#tema-status-' + temaIndex).text('Erro na geração');
            }
        }

        $('#mass-action-warning').hide();
    }

    async function publicarConteudo(indices) {
        for (const temaIndex of indices) {
            $('#tema-status-' + temaIndex).text('Publicando...');
            $('#progress-bar-publicar-' + temaIndex).show();
            var progressBar = $('#progress-bar-publicar-' + temaIndex + ' .progress-bar-fill');

            var width = 0;
            var interval = setInterval(() => {
                if (width >= 100) clearInterval(interval);
                else {
                    width += 10;
                    progressBar.css('width', width + '%');
                }
            }, 1000);

            try {
                const response = await $.post(ajaxurl, {
                    action: 'publicar_tema',
                    tema_index: temaIndex
                });

                clearInterval(interval);
                $('#progress-bar-publicar-' + temaIndex).hide();

                if (response.success) {
                    const link = response.data.link;
                    $('#tema-status-' + temaIndex).text('Publicado');
                    $('.gerar-conteudo[data-tema="' + temaIndex + '"]').replaceWith(`<a href="${link}" target="_blank" class="button ver-conteudo">Ver Conteúdo</a>`);
                } else {
                    $('#tema-status-' + temaIndex).text('Erro ao publicar');
                }
            } catch (e) {
                clearInterval(interval);
                $('#progress-bar-publicar-' + temaIndex).hide();
                $('#tema-status-' + temaIndex).text('Erro');
            }
        }

        $('#mass-action-warning').hide();

        alert('Todos os conteúdos foram publicados com sucesso!');

        indices.forEach(function (temaIndex) {
            $('tr[data-status]').filter(function () {
                return $(this).find('.publicar-tema').data('tema') == temaIndex;
            }).fadeOut(300, function () {
                $(this).remove();
            });
        });
    }

    // Função para limpar todos os temas
    $('#limpar-todos-temas').on('click', function () {
        var totalTemas = $('.selecionar-tema').length;
        
        if (totalTemas === 0) {
            alert('Não há temas para remover.');
            return;
        }

        if (!confirm(`Tem certeza que deseja remover todos os ${totalTemas} temas da lista? Esta ação não pode ser desfeita.`)) {
            return;
        }

        // Selecionar todos os checkboxes
        $('.selecionar-tema').prop('checked', true);
        $('#selecionar-todos').prop('checked', true);

        // Coletar todos os índices dos temas
        var todosTemas = $('.selecionar-tema').map(function () {
            return $(this).val();
        }).get();

        // Executar exclusão em massa
        $('#mass-action-warning').show();
        
        // Reduzir a opacidade de todas as linhas
        $('.selecionar-tema').closest('tr').css('opacity', '0.5');

        $.post(ajaxurl, {
            action: 'excluir_temas_em_massa',
            temas: todosTemas
        }, function (response) {
            $('#mass-action-warning').hide();
            
            if (response.success) {
                // Remover todas as linhas da tabela
                $('.selecionar-tema').closest('tr').fadeOut(400, function () {
                    $(this).remove();
                });
                alert('Todos os temas foram removidos com sucesso!');
                
                // Desmarcar o checkbox "selecionar todos"
                $('#selecionar-todos').prop('checked', false);
            } else {
                // Restaurar a opacidade em caso de erro
                $('.selecionar-tema').closest('tr').css('opacity', '1');
                alert('Erro ao remover temas: ' + response.data);
            }
        }).fail(function (xhr, status, error) {
            $('#mass-action-warning').hide();
            // Restaurar a opacidade em caso de erro
            $('.selecionar-tema').closest('tr').css('opacity', '1');
            alert('Erro na requisição: ' + error);
        });
    });
});