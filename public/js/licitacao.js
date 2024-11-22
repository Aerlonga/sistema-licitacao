
document.addEventListener('DOMContentLoaded', function () {
    inicializarTabelaLicitacoes();
});

$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

// Função para inicializar a tabela de licitações
function inicializarTabelaLicitacoes() {
    const dataTableLicitacoes = document.getElementById('licitacoesTable');

    if (dataTableLicitacoes) {
        if ($.fn.DataTable.isDataTable(dataTableLicitacoes)) {
            $(dataTableLicitacoes).DataTable().destroy();
        }
        //resolver button
        $(dataTableLicitacoes).DataTable({
            dom: 'Bfrtip',
            buttons: [
                {
                    titleAttr: 'Exportar para excel',
                    extend: 'excel',
                    text: '<i class="fas fa-file-excel"></i>',
                    className: 'btn btn-success btn-excel',
                    exportOptions: {
                        columns: ':not(:last-child)'
                    }
                }
            ],
            paging: true,
            searching: true,
            info: true,
            pageLength: 10,
            autoWidth: false,
            scrollX: true,
            ajax: {
                url: $(dataTableLicitacoes).data('url'),
                type: 'GET',
                dataSrc: ''
            },
            columns: [
                { data: 'sei' },
                { data: 'sislog' },
                { data: 'modalidade' },
                { data: 'objeto_contratacao' },
                { data: 'gestor.nome' },
                { data: 'integrante.nome' },
                { data: 'fiscal.nome' },
                {
                    data: 'created_at',
                    render: function (data) {
                        return data ? moment(data).format('DD/MM/YYYY HH:mm:ss') : '';
                    }
                },
                { data: 'situacao' },
                { data: 'local' },
                {
                    data: null,
                    render: function (data, type, row) {
                        return `
                            <button type="button" class="btn btn-primary" onclick="abrirModalEditar(${data.id_licitacao})">
                                Editar
                            </button>
                        `;
                    },
                    orderable: false,
                    searchable: false
                }
            ],
            language: {
                url: '/vendor/adminlte/plugins/datatables/plugins/i18n/pt-BR.json'
            }
        });
    } else {
        console.error('Elemento "licitacoesTable" não encontrado.');
    }
}


// Alternar para o modo de edição
function toggleEditMode() {
    const fields = [
        'modalidadeText', 'objetoText', 'gestorText', 'integranteText', 'fiscalText',
        'dataInclusaoText', 'seiText', 'sislogText', 'situacaoText', 'localText', 'observacaoText'
    ];

    fields.forEach(field => {
        const element = document.getElementById(field);
        const text = element.innerText;
        element.innerHTML = `<input type="text" value="${text}" class="form-control">`;
    });

    document.getElementById('salvarButton').style.display = 'inline-block';
}


function salvarEdicao() {
    const licitacaoId = $('#visualizarLicitacaoModal').data('licitacaoId');

    const data = {
        // Para campos não editáveis, vamos buscar o texto diretamente do `span`
        modalidade: $('#modalidadeText span').text(),
        objeto_contratacao: $('#objetoText span').text(),
        gestor: $('#gestorText span').text(),
        integrante: $('#integranteText span').text(),
        fiscal: $('#fiscalText span').text(),
        data_inclusao: $('#dataInclusaoText span').text(),
        sei: $('#seiText span').text(),
        sislog: $('#sislogText span').text(),

        // Para campos `select`, obtenha o valor selecionado
        situacao: $('#situacaoText select').val(),
        local: $('#localText select').val(),

        // Para o campo de observação, obtenha o valor do input
        observacao: $('#observacaoText input').val()
    };

    // Desativa o botão de salvar para evitar múltiplos cliques
    $('#salvarButton').prop('disabled', true);

    $.ajax({
        url: `/licitacoes/${licitacaoId}`,
        method: 'PUT',
        data: data,
        success: function (response) {
            alert('Alterações salvas com sucesso!');
            $('#visualizarLicitacaoModal').modal('hide');
            inicializarTabelaLicitacoes(); // Recarrega a tabela após salvar
        },
        error: function (xhr) {
            console.error('Erro ao salvar alterações:', xhr.responseText);
            alert('Erro ao salvar alterações.');
        },
        complete: function () {
            // Reativa o botão de salvar após a conclusão da requisição, com sucesso ou erro
            $('#salvarButton').prop('disabled', false);
        }
    });
}


// Função para cancelar a edição
function cancelarEdicao() {
    const licitacaoId = $('#visualizarLicitacaoModal').data('licitacaoId');
    abrirModalEditar(licitacaoId); // Reabre o modal com dados originais
}

function excluirLicitacao(id) {
    SweetAlertGoInfra.confirmar("Tem certeza que deseja excluir esta licitação", function (isConfirmed) {
        if (isConfirmed) {
            $.ajax({
                url: `/licitacoes/${id}`, // URL para o endpoint de atualização
                type: 'PUT', // Método PUT para atualização
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                data: {
                    status: 0 // Define o status como inativo
                },
                success: function (response) {
                    // Usar SweetAlert para mensagem de sucesso
                    Swal.fire({
                        title: 'Licitação excluída com sucesso!',
                        text: '',
                        icon: 'success',
                        confirmButtonText: 'OK'
                    }).then(() => {
                        $('#licitacoesTable').DataTable().ajax.reload(); // Recarrega a tabela
                        $('#visualizarLicitacaoModal').modal('hide'); // Fecha o modal
                    });
                },
                error: function (xhr) {
                    console.error('Erro ao excluir a licitação:', xhr.responseText);
                    // Usar SweetAlert para mensagens de erro
                    Swal.fire({
                        title: 'Erro',
                        text: 'Houve um erro ao tentar excluir a licitação.',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                }
            });
        } else {
            SweetAlertGoInfra.alerta('Exclusão cancelada.');
        }
    });
}


// Função para abrir o modal de edição com os detalhes da licitação
function abrirModalEditar(id) {
    $.ajax({
        url: `/licitacoes/${id}/show`,
        method: 'GET',
        success: function (data) {
            // Exemplo de variável isAdmin que você pode definir globalmente ou passar do backend
            var isAdmin = typeof window.isAdmin !== 'undefined' ? window.isAdmin : false;

            // Renderizar campos com base no tipo de usuário
            $('#modalidadeText').html(isAdmin ? `<input type="text" class="form-control" value="${data.modalidade}">` : `<span>${data.modalidade}</span>`);
            $('#objetoText').html(isAdmin ? `<input type="text" class="form-control" value="${data.objeto_contratacao}">` : `<span>${data.objeto_contratacao}</span>`);
            $('#gestorText').html(isAdmin ? `<input type="text" class="form-control" value="${data.gestor ? data.gestor.nome : 'N/A'}">` : `<span>${data.gestor ? data.gestor.nome : 'N/A'}</span>`);
            $('#integranteText').html(isAdmin ? `<input type="text" class="form-control" value="${data.integrante ? data.integrante.nome : 'N/A'}">` : `<span>${data.integrante ? data.integrante.nome : 'N/A'}</span>`);
            $('#fiscalText').html(isAdmin ? `<input type="text" class="form-control" value="${data.fiscal ? data.fiscal.nome : 'N/A'}">` : `<span>${data.fiscal ? data.fiscal.nome : 'N/A'}</span>`);
            $('#dataInclusaoText').html(`<span>${data.created_at ? moment(data.created_at).format('DD/MM/YYYY HH:mm:ss') : ''}</span>`);
            $('#seiText').html(isAdmin ? `<input type="text" class="form-control" value="${data.sei}">` : `<span>${data.sei}</span>`);
            $('#sislogText').html(isAdmin ? `<input type="text" class="form-control" value="${data.sislog}">` : `<span>${data.sislog}</span>`);

            // Campos editáveis para todos os usuários (incluindo não administradores)
            $('#situacaoText').html(`
                <select class="form-control" ${isAdmin ? '' : ''}>
                    <option value="Em andamento" ${data.situacao === 'Em andamento' ? 'selected' : ''}>Em andamento</option>
                    <option value="Em outro setor" ${data.situacao === 'Em outro setor' ? 'selected' : ''}>Em outro setor</option>
                    <option value="Finalizado" ${data.situacao === 'Finalizado' ? 'selected' : ''}>Finalizado</option>
                </select>
            `);

            $('#localText').html(`
                <select class="form-control" ${isAdmin ? '' : ''}>
                    <option value="TR e/ou ETP" ${data.local === 'TR e/ou ETP' ? 'selected' : ''}>TR e/ou ETP</option>
                    <option value="GELIC e GEORC" ${data.local === 'GELIC e GEORC' ? 'selected' : ''}>GELIC e GEORC</option>
                    <option value="GELIC" ${data.local === 'GELIC' ? 'selected' : ''}>GELIC</option>
                    <option value="GEORC" ${data.local === 'GEORC' ? 'selected' : ''}>GEORC</option>
                    <option value="PROSET" ${data.local === 'PROSET' ? 'selected' : ''}>PROSET</option>
                    <option value="PR" ${data.local === 'PR' ? 'selected' : ''}>PR</option>
                    <option value="CACTIC" ${data.local === 'CACTIC' ? 'selected' : ''}>CACTIC</option>
                </select>
            `);

            $('#observacaoText').html(`<input type="text" class="form-control" value="${data.observacao ? data.observacao : ''}">`);

            $('#visualizarLicitacaoModal').modal('show');
            $('#visualizarLicitacaoModal').data('licitacaoId', id);
            $('#salvarButton').show();
        },
        error: function (xhr) {
            console.error('Erro ao carregar os detalhes da licitação:', xhr.responseText);
            alert('Erro ao carregar os detalhes da licitação.');
        }
    });
}
