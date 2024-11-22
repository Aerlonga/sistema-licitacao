// document.addEventListener('DOMContentLoaded', function () {
//     const dataTableId = 'dataTable';

//     function dataTable() {
//         let dataTable = document.getElementById(dataTableId);

//         if (dataTable) {
//             $('#' + dataTableId).DataTable({
//                 paging: true,
//                 searching: true,
//                 info: true,
//                 pageLength: 10,
//                 ajax: {
//                     url: $('#' + dataTableId).data('url'),
//                     type: 'GET',
//                     dataSrc: ''
//                 },
//                 columns: [
//                     { data: 'sei' },
//                     { data: 'sislog' },
//                     { data: 'modalidade' },
//                     { data: 'id_licitacao' },
//                     { data: 'objeto_contratacao' },
//                     { data: 'gestor.nome' },
//                     { data: 'integrante.nome' },
//                     { data: 'fiscal.nome' },
//                     {
//                         data: 'created_at',
//                         render: function (data) {
//                             return data ? moment(data).format('DD/MM/YYYY HH:mm:ss') : '';
//                         }
//                     },
//                     {
//                         data: 'situacao',
//                         render: function (data, type, row) {
//                             return `
//                                 <select class="form-control situacao-select" data-id="${row.id_licitacao}">
//                                     <option value="opcao1" ${data === 'opcao1' ? 'selected' : ''}>Opção 1</option>
//                                     <option value="opcao2" ${data === 'opcao2' ? 'selected' : ''}>Opção 2</option>
//                                     <option value="opcao3" ${data === 'opcao3' ? 'selected' : ''}>Opção 3</option>
//                                 </select>
//                             `;
//                         }
//                     },
//                     {
//                         data: 'local',
//                         render: function (data, type, row) {
//                             return `
//                                 <select class="form-control local-select" data-id="${row.id_licitacao}">
//                                     <option value="opcao1" ${data === 'opcao1' ? 'selected' : ''}>Opção 1</option>
//                                     <option value="opcao2" ${data === 'opcao2' ? 'selected' : ''}>Opção 2</option>
//                                     <option value="opcao3" ${data === 'opcao3' ? 'selected' : ''}>Opção 3</option>
//                                 </select>
//                             `;
//                         }
//                     },
//                     {
//                         data: 'id_licitacao',
//                         render: function (data) {
//                             return `
//                                 <button class="btn btn-primary btn-sm" onclick="salvarAlteracoes(${data})">Salvar</button>
//                                 <button class="btn btn-danger btn-sm" onclick="excluirLicitacao(${data})">Excluir</button>
//                             `;
//                         },
//                         orderable: false,
//                         searchable: false
//                     }
//                 ],
//                 language: {
//                     url: '/vendor/adminlte/plugins/datatables/plugins/i18n/pt-BR.json'
//                 }
//             });
//         }
//     }

//     dataTable();
// });

// // Função para salvar as alterações da licitação
// function salvarAlteracoes(id) {
//     const situacao = $(`.situacao-select[data-id="${id}"]`).val();
//     const local = $(`.local-select[data-id="${id}"]`).val();

//     $.ajax({
//         url: `/licitacoes/${id}`,
//         type: 'PUT',
//         data: {
//             _token: $('meta[name="csrf-token"]').attr('content'),
//             situacao: situacao,
//             local: local
//         },
//         success: function (response) {
//             alert('Situação e Local atualizados com sucesso!');
//             $('#dataTable').DataTable().ajax.reload();
//         },
//         error: function () {
//             alert('Erro ao atualizar situação e local.');
//         }
//     });
// }

// // Função para excluir uma licitação
// function excluirLicitacao(id) {
//     if (confirm("Tem certeza que deseja excluir esta licitação?")) {
//         $.ajax({
//             url: `/licitacoes/${id}`,
//             type: 'DELETE',
//             headers: {
//                 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
//             },
//             success: function (response) {
//                 alert('Licitação excluída com sucesso!');
//                 $('#dataTable').DataTable().ajax.reload();
//             },
//             error: function () {
//                 alert('Erro ao excluir a licitação.');
//             }
//         });
//     }
// }
