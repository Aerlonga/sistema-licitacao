document.addEventListener('DOMContentLoaded', function () {
    inicializarTabelaPessoas();
});

const formPessoa = document.getElementById('formPessoa');
if (formPessoa) {
    formPessoa.addEventListener('submit', function (event) {
        event.preventDefault(); // Previne o comportamento padrão de recarregar a página
        salvarPessoa(); // Chama a função de salvar ao pressionar Enter
    });
}



function inicializarTabelaPessoas() {
    const pessoasTable = document.getElementById('pessoasTable'); // Corrige a referência para a tabela

    if (pessoasTable) {
        if ($.fn.DataTable.isDataTable(pessoasTable)) {
            $(pessoasTable).DataTable().destroy(); // Destroi a instância anterior, se existir
        }

        $(pessoasTable).DataTable({
            dom: "<'row'<'col-sm-12 col-md-6'B><'col-sm-12 col-md-6 text-right'f>>" +
                "<'row'<'col-sm-12'tr>>" +
                "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
            buttons: [
                {
                    titleAttr: 'Exportar para Excel',
                    extend: 'excel',
                    text: '<i class="fas fa-file-excel"></i>',
                    className: 'btn btn-success btn-excel',
                    exportOptions: {
                        columns: ':not(:last-child)', // Excluir a coluna de ações da exportação
                    },
                },
                {
                    text: '<i class="fas fa-plus"></i> Adicionar Pessoa',
                    className: 'btn btn-primary',
                    action: function () {
                        abrirModalCriar(); // Chama a função para abrir o modal
                    },
                },
            ],
            paging: true,
            searching: true,
            info: true,
            pageLength: 10,
            autoWidth: false,
            scrollX: true,
            ajax: {
                url: $(pessoasTable).data('url'),
                type: 'GET',
                dataSrc: '',
                error: function (xhr, status, error) {
                    console.error('Erro no DataTables:', xhr.responseText);
                }
            },
            columns: [
                { data: 'nome' }, // Nome da pessoa
                { data: 'sobrenome' }, // Sobrenome da pessoa
                {
                    data: null,
                    render: function (data) {
                        return `
                            <button class="btn btn-warning btn-sm" 
                                onclick="abrirModalEditar(${data.id_pessoa}, '${data.nome}', '${data.sobrenome}')">Editar</button>
                        `;
                    },
                    orderable: false,
                    searchable: false,
                },
            ],
            language: {
                url: '/vendor/adminlte/plugins/datatables/plugins/i18n/pt-BR.json', // Traduz para português
            },
        });
    } else {
        console.error('Elemento "pessoasTable" não encontrado.');
    }
}



function abrirModalCriar() {
    // Limpa os valores do modal para criação
    document.getElementById('pessoaId').value = '';
    document.getElementById('pessoaNome').value = '';
    document.getElementById('pessoaSobrenome').value = '';
    document.getElementById('modalPessoaLabel').textContent = 'Adicionar Pessoa';

    // Oculta o botão de exclusão
    const excluirButton = document.getElementById('excluirPessoaButton');
    if (excluirButton) {
        excluirButton.style.display = 'none';
    }

    $('#modalPessoa').modal('show');
}


function abrirModalEditar(id, nome, sobrenome) {
    // Preenche os valores do modal para edição
    document.getElementById('pessoaId').value = id;
    document.getElementById('pessoaNome').value = nome;
    document.getElementById('pessoaSobrenome').value = sobrenome; // Adiciona o sobrenome
    document.getElementById('modalPessoaLabel').textContent = 'Editar Pessoa';

    // Exibe o botão de exclusão e associa o ID ao botão
    const excluirButton = document.getElementById('excluirPessoaButton');
    if (excluirButton) {
        excluirButton.style.display = 'inline-block';
        excluirButton.setAttribute('data-id', id);
    }

    $('#modalPessoa').modal('show');
}


// function salvarPessoa() {
//     const id = document.getElementById('pessoaId').value; // ID da pessoa no campo oculto
//     const nome = document.getElementById('pessoaNome').value.trim(); // Nome no formulário
//     const sobrenome = document.getElementById('pessoaSobrenome').value.trim(); // Sobrenome no formulário

//     if (!nome || !sobrenome) {
//         Swal.fire('Erro!', 'Os campos nome e sobrenome são obrigatórios.', 'error');
//         return;
//     }

//     // Decida se será criação ou edição com base no ID
//     const url = id ? `/pessoas/${id}` : '/pessoas';
//     const method = id ? 'PUT' : 'POST';

//     fetch(url, {
//         method: method,
//         headers: {
//             'Content-Type': 'application/json',
//             'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
//         },
//         body: JSON.stringify({ nome, sobrenome }),
//     })
//         .then((response) => {
//             if (!response.ok) {
//                 return response.json().then((data) => {
//                     throw new Error(data.error || 'Erro ao salvar os dados.');
//                 });
//             }
//             return response.json();
//         })
//         .then((data) => {
//             $('#modalPessoa').modal('hide'); // Fecha o modal
//             Swal.fire('Sucesso!', data.success || 'Pessoa salva com sucesso.', 'success');
//             $('#pessoasTable').DataTable().ajax.reload(); // Recarrega a tabela
//         })
//         .catch((error) => {
//             console.error('Erro ao salvar pessoa:', error);
//             Swal.fire('Erro!', error.message || 'Erro ao salvar pessoa.', 'error');
//         });
// }

function salvarPessoa() {
    const id = document.getElementById('pessoaId').value; // ID da pessoa no campo oculto
    const nome = document.getElementById('pessoaNome').value.trim(); // Nome no formulário
    const sobrenome = document.getElementById('pessoaSobrenome').value.trim(); // Sobrenome no formulário

    if (!nome || !sobrenome) {
        Swal.fire('Erro!', 'Os campos nome e sobrenome são obrigatórios.', 'error');
        return;
    }

    const url = id ? `/pessoas/${id}` : '/pessoas';
    const method = id ? 'PUT' : 'POST';

    fetch(url, {
        method: method,
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
        },
        body: JSON.stringify({ nome, sobrenome }),
    })
        .then((response) => {
            if (!response.ok) {
                if (response.status === 405) {
                    throw new Error('Nome ou Sobrenome não permitido! Favor digite apenas letras!');
                }

                return response.json().then((data) => {
                    throw new Error(data.error || 'Erro ao salvar os dados.');
                });
            }
            return response.json();
        })
        .then((data) => {
            $('#modalPessoa').modal('hide'); // Fecha o modal
            Swal.fire('Sucesso!', data.success || 'Pessoa salva com sucesso.', 'success');
            $('#pessoasTable').DataTable().ajax.reload(); // Recarrega a tabela
        })
        .catch((error) => {
            console.error('Erro ao salvar pessoa:', error.message);

            // Exibe a mensagem de erro detalhada
            Swal.fire('Erro!', error.message || 'Erro ao salvar pessoa.', 'error');
        });
}


function excluirPessoaModal() {
    const id = document.getElementById('excluirPessoaButton').getAttribute('data-id');
    if (!id) {
        Swal.fire('Erro!', 'ID da pessoa não encontrado.', 'error');
        return;
    }

    Swal.fire({
        title: 'Tem certeza?',
        text: 'Essa pessoa será excluída!',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Sim, continuar!',
        cancelButtonText: 'Cancelar',
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
    }).then((result) => {
        if (result.isConfirmed) {
            fetch(`/pessoas/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json',
                },
            })
                .then((response) => {
                    if (!response.ok) {
                        return response.json().then((data) => {
                            throw new Error(data.error || 'Erro desconhecido.');
                        });
                    }
                    return response.json();
                })
                .then((data) => {
                    Swal.fire('Sucesso!', data.success || 'Pessoa excluída com sucesso!', 'success');
                    $('#pessoasTable').DataTable().ajax.reload(); // Recarrega a tabela
                    $('#modalPessoa').modal('hide'); // Fecha o modal
                })
                .catch((error) => {
                    Swal.fire('Erro!', error.message || 'Não foi possível excluir a pessoa.', 'error');
                });
        }
    });
}
