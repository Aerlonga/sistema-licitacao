
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


// function salvarEdicao() {
//     const licitacaoId = $('#visualizarLicitacaoModal').data('licitacaoId');

//     // Captura os valores dos campos
//     const data = {
//         modalidade: $('#modalidadeText input').val() || null,
//         objeto_contratacao: $('#objetoText input').length
//             ? $('#objetoText input').val().trim()
//             : $('#objetoText span').text().trim(),
//         gestor: $('#gestorText input').val() || null,
//         integrante: $('#integranteText input').val() || null,
//         fiscal: $('#fiscalText input').val() || null,
//         data_inclusao: $('#dataInclusaoText span').text().trim() || null, // Somente leitura
//         sei: $('#seiText input').val() || null,
//         sislog: $('#sislogText input').val() || null,
//         situacao: $('#situacaoText select').val() || null,
//         local: $('#localText select').val() || null,
//         observacao: $('#observacaoText input').val() || null,
//     };

//     // Valida os campos obrigatórios no frontend (opcional)
//     if (!data.objeto_contratacao) {
//         Swal.fire({
//             title: 'Erro',
//             text: 'O campo "Objeto da Contratação" é obrigatório.',
//             icon: 'error',
//             confirmButtonText: 'OK'
//         });
//         return;
//     }

//     // Desativa o botão de salvar para evitar múltiplos cliques
//     $('#salvarButton').prop('disabled', true);

//     // Envia a requisição AJAX
//     $.ajax({
//         url: `/licitacoes/${licitacaoId}`,
//         method: 'PUT',
//         data: data,
//         success: function (response) {
//             // Exibe mensagem de sucesso
//             Swal.fire({
//                 title: 'Alterações Salvas!',
//                 text: 'As alterações foram salvas com sucesso.',
//                 icon: 'success',
//                 confirmButtonText: 'OK'
//             }).then(() => {
//                 $('#visualizarLicitacaoModal').modal('hide');
//                 inicializarTabelaLicitacoes(); // Recarrega a tabela
//             });
//         },
//         error: function (xhr) {
//             console.error('Erro ao salvar alterações:', xhr.responseText);

//             // Exibe mensagem de erro
//             Swal.fire({
//                 title: 'Erro',
//                 text: 'Houve um erro ao salvar as alterações. Verifique os campos obrigatórios.',
//                 icon: 'error',
//                 confirmButtonText: 'OK'
//             });
//         },
//         complete: function () {
//             // Reativa o botão de salvar após a conclusão da requisição
//             $('#salvarButton').prop('disabled', false);
//         }
//     });
// }

// Função para cancelar a edição

function salvarEdicao() {
    const licitacaoId = $('#visualizarLicitacaoModal').data('licitacaoId');

    // Dados capturados do modal
    const data = {
        modalidade: $('#modalidadeText input').val() || null,
        objeto_contratacao: $('#objetoText input').val()?.trim() || null,
        id_gestor: $('#gestorText select').val() || null,
        id_integrante: $('#integranteText select').val() || null,
        id_fiscal: $('#fiscalText select').val() || null,
        sei: $('#seiText input').val()?.trim() || null,
        sislog: $('#sislogText input').val()?.trim() || null,
        situacao: $('#situacaoText select').val() || null,
        local: $('#localText select').val() || null,
        observacao: $('#observacaoText input').val()?.trim() || null,
    };

     // Log para verificação
     console.log(data);
    
    // Requisição AJAX
    $.ajax({
        url: `/licitacoes/${licitacaoId}`,
        method: 'PUT',
        data: data,
        success: function (response) {
            Swal.fire('Sucesso!', 'Alterações salvas com sucesso.', 'success');
            $('#visualizarLicitacaoModal').modal('hide');
            $('#licitacoesTable').DataTable().ajax.reload();
        },
        error: function (xhr) {
            Swal.fire('Erro!', 'Erro ao salvar as alterações.', 'error');
            console.error(xhr.responseText);
        },
    });
}



function cancelarEdicao() {
    const licitacaoId = $('#visualizarLicitacaoModal').data('licitacaoId');
    abrirModalEditar(licitacaoId); // Reabre o modal com dados originais
}

function excluirLicitacao(id) {
    Swal.fire({
        title: 'Tem certeza que deseja excluir esta licitação?',
        text: 'Essa ação não poderá ser desfeita.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sim, excluir!',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
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
                        title: 'Excluído!',
                        text: 'Licitação excluída com sucesso.',
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
                        title: 'Erro!',
                        text: 'Houve um erro ao tentar excluir a licitação.',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                }
            });
        }
    });
}



// function abrirModalEditar(id) {
//     $.ajax({
//         url: `/licitacoes/${id}/show`,
//         method: 'GET',
//         success: function (response) {
//             const data = response.data;
//             const isAdmin = response.isAdmin;

//             // Renderiza os campos com base no tipo de usuário
//             $('#modalidadeText').html(isAdmin
//                 ? `<input type="text" class="form-control" value="${data.modalidade}">`
//                 : `<span>${data.modalidade || 'N/A'}</span>`);

//             $('#objetoText').html(isAdmin
//                 ? `<input type="text" class="form-control" value="${data.objeto_contratacao || ''}">`
//                 : `<span>${data.objeto_contratacao || ''}</span>`);

//             $('#gestorText').html(isAdmin
//                 ? `<select class="form-control">
//                     <option value="Vitor" ${data.gestor === 'Vitor' ? 'selected' : ''}>Vitor</option>
//                     <option value="Thaynara" ${data.gestor === 'Thaynara' ? 'selected' : ''}>Thaynara</option>
//                     <option value="Francielle" ${data.gestor === 'Francielle' ? 'selected' : ''}>Francielle</option>
//                    </select>`
//                 : `<span>${data.gestor || 'N/A'}</span>`);

//             $('#integranteText').html(isAdmin
//                 ? `<select class="form-control">
//                     <option value="Vitor" ${data.integrante === 'Vitor' ? 'selected' : ''}>Vitor</option>
//                     <option value="Thaynara" ${data.integrante === 'Thaynara' ? 'selected' : ''}>Thaynara</option>
//                     <option value="Francielle" ${data.integrante === 'Francielle' ? 'selected' : ''}>Francielle</option>
//                    </select>`
//                 : `<span>${data.integrante || 'N/A'}</span>`);

//             $('#fiscalText').html(isAdmin
//                 ? `<select class="form-control">
//                     <option value="Vitor" ${data.fiscal === 'Vitor' ? 'selected' : ''}>Vitor</option>
//                     <option value="Thaynara" ${data.fiscal === 'Thaynara' ? 'selected' : ''}>Thaynara</option>
//                     <option value="Francielle" ${data.fiscal === 'Francielle' ? 'selected' : ''}>Francielle</option>
//                    </select>`
//                 : `<span>${data.fiscal || 'N/A'}</span>`);

//             $('#dataInclusaoText').html(`<span>${data.data_inclusao || 'N/A'}</span>`);
//             $('#seiText').html(isAdmin
//                 ? `<input type="text" class="form-control" value="${data.sei}">`
//                 : `<span>${data.sei || 'N/A'}</span>`);

//             $('#sislogText').html(isAdmin
//                 ? `<input type="text" class="form-control" value="${data.sislog}">`
//                 : `<span>${data.sislog || 'N/A'}</span>`);

//             // Campos editáveis para todos os usuários
//             $('#situacaoText').html(` 
//                 <select class="form-control">
//                     <option value="Em andamento" ${data.situacao === 'Em andamento' ? 'selected' : ''}>Em andamento</option>
//                     <option value="Em outro setor" ${data.situacao === 'Em outro setor' ? 'selected' : ''}>Em outro setor</option>
//                     <option value="Finalizado" ${data.situacao === 'Finalizado' ? 'selected' : ''}>Finalizado</option>
//                 </select>
//             `);

//             $('#localText').html(`
//                 <select class="form-control">
//                     <option value="TR e/ou ETP" ${data.local === 'TR e/ou ETP' ? 'selected' : ''}>TR e/ou ETP</option>
//                     <option value="GELIC e GEORC" ${data.local === 'GELIC e GEORC' ? 'selected' : ''}>GELIC e GEORC</option>
//                     <option value="GELIC" ${data.local === 'GELIC' ? 'selected' : ''}>GELIC</option>
//                     <option value="GEORC" ${data.local === 'GEORC' ? 'selected' : ''}>GEORC</option>
//                     <option value="PROSET" ${data.local === 'PROSET' ? 'selected' : ''}>PROSET</option>
//                     <option value="PR" ${data.local === 'PR' ? 'selected' : ''}>PR</option>
//                     <option value="CACTIC" ${data.local === 'CACTIC' ? 'selected' : ''}>CACTIC</option>
//                 </select>
//             `);

//             $('#observacaoText').html(`<input type="text" class="form-control" value="${data.observacao || ''}">`);

//             // Exibe o modal
//             $('#visualizarLicitacaoModal').modal('show');
//             $('#visualizarLicitacaoModal').data('licitacaoId', id);
//         },
//         error: function (xhr) {
//             console.error('Erro ao carregar os detalhes da licitação:', xhr.responseText);
//             Swal.fire('Erro', 'Não foi possível carregar os detalhes da licitação.', 'error');
//         }
//     })
// }

function abrirModalEditar(id) {
    $.ajax({
        url: `/licitacoes/${id}/show`,
        method: 'GET',
        success: function (response) {
            const data = response.data;
            const isAdmin = response.isAdmin;
            const pessoas = data.pessoas || []; // Carrega as pessoas disponíveis

            // Gera as opções dinamicamente para o select
            const gerarOpcoes = (selectedId) => {
                return pessoas
                    .map(pessoa =>
                        `<option value="${pessoa.id}" ${pessoa.id === selectedId ? 'selected' : ''}>${pessoa.nome}</option>`
                    )
                    .join('');
            };

            // Preenche os campos do modal
            $('#modalidadeText').html(isAdmin
                ? `<input type="text" class="form-control" value="${data.modalidade}">`
                : `<span>${data.modalidade || 'N/A'}</span>`);

            $('#objetoText').html(isAdmin
                ? `<input type="text" class="form-control" value="${data.objeto_contratacao || ''}">`
                : `<span>${data.objeto_contratacao || ''}</span>`);

            $('#gestorText').html(isAdmin
                ? `<select class="form-control">${gerarOpcoes(data.gestor_id)}</select>`
                : `<span>${data.gestor || 'N/A'}</span>`);

            $('#integranteText').html(isAdmin
                ? `<select class="form-control">${gerarOpcoes(data.integrante_id)}</select>`
                : `<span>${data.integrante || 'N/A'}</span>`);

            $('#fiscalText').html(isAdmin
                ? `<select class="form-control">${gerarOpcoes(data.fiscal_id)}</select>`
                : `<span>${data.fiscal || 'N/A'}</span>`);

            $('#dataInclusaoText').html(`<span>${data.data_inclusao || 'N/A'}</span>`);

            $('#seiText').html(isAdmin
                ? `<input type="text" class="form-control" value="${data.sei}">`
                : `<span>${data.sei || 'N/A'}</span>`);

            $('#sislogText').html(isAdmin
                ? `<input type="text" class="form-control" value="${data.sislog}">`
                : `<span>${data.sislog || 'N/A'}</span>`);

            $('#situacaoText').html(`
                <select class="form-control">
                    <option value="Em andamento" ${data.situacao === 'Em andamento' ? 'selected' : ''}>Em andamento</option>
                    <option value="Em outro setor" ${data.situacao === 'Em outro setor' ? 'selected' : ''}>Em outro setor</option>
                    <option value="Finalizado" ${data.situacao === 'Finalizado' ? 'selected' : ''}>Finalizado</option>
                </select>
            `);

            $('#localText').html(`
                <select class="form-control">
                    <option value="TR e/ou ETP" ${data.local === 'TR e/ou ETP' ? 'selected' : ''}>TR e/ou ETP</option>
                    <option value="GELIC e GEORC" ${data.local === 'GELIC e GEORC' ? 'selected' : ''}>GELIC e GEORC</option>
                    <option value="GELIC" ${data.local === 'GELIC' ? 'selected' : ''}>GELIC</option>
                    <option value="GEORC" ${data.local === 'GEORC' ? 'selected' : ''}>GEORC</option>
                    <option value="PROSET" ${data.local === 'PROSET' ? 'selected' : ''}>PROSET</option>
                    <option value="PR" ${data.local === 'PR' ? 'selected' : ''}>PR</option>
                    <option value="CACTIC" ${data.local === 'CACTIC' ? 'selected' : ''}>CACTIC</option>
                </select>
            `);

            $('#observacaoText').html(`<input type="text" class="form-control" value="${data.observacao || ''}">`);

            // Exibe o modal
            $('#visualizarLicitacaoModal').modal('show');
            $('#visualizarLicitacaoModal').data('licitacaoId', id);
        },
        error: function (xhr) {
            console.error('Erro ao carregar os detalhes da licitação:', xhr.responseText);
            Swal.fire('Erro', 'Não foi possível carregar os detalhes da licitação.', 'error');
        }
    });
}