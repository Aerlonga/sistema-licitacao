document.addEventListener('DOMContentLoaded', function () {
    carregarPessoas();

    // Submit do formulário do modal
    document.getElementById('formPessoa').addEventListener('submit', function (event) {
        event.preventDefault();
        salvarPessoa();
    });
});

// Inicializando o DataTable\
$(document).ready(function () {
    $('#pessoasTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '/pessoas/data',  // A URL que retorna os dados em formato JSON
            method: 'GET',
            dataSrc: ''
        },
        columns: [
            { data: 'id_pessoa' },
            { data: 'nome' },
            {
                data: null,
                render: function (data, type, row) {
                    return `
                        <button class="btn btn-warning btn-sm" onclick="abrirModalEditar(${data.id_pessoa}, '${data.nome}')">Editar</button>
                        <button class="btn btn-danger btn-sm" onclick="excluirPessoa(${data.id_pessoa})">Excluir</button>
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
});

function carregarPessoas() {
    const tabela = document.getElementById('pessoasTable');
    if (!tabela) {
        console.error('Elemento "pessoasTable" não encontrado no DOM.');
        return;
    }

    fetch("/pessoas")
        .then(response => response.json())
        .then(data => {
            tabela.innerHTML = ''; // Limpa a tabela

            data.forEach(pessoa => {
                tabela.innerHTML += `
                    <tr>
                        <td>${pessoa.id_pessoa}</td>
                        <td>${pessoa.nome}</td>
                        <td>
                            <button class="btn btn-warning btn-sm" onclick="abrirModalEditar(${pessoa.id_pessoa}, '${pessoa.nome}')">Editar</button>
                            <button class="btn btn-danger btn-sm" onclick="excluirPessoa(${pessoa.id_pessoa})">Excluir</button>
                        </td>
                    </tr>
                `;
            });
        })
        .catch(error => {
            console.error('Erro ao carregar pessoas:', error);
            Swal.fire('Erro!', 'Não foi possível carregar a lista de pessoas.', 'error');
        });
}

function abrirModalEditar(id, nome) {
    // Armazenar o ID da pessoa no modal usando data()
    $('#modalPessoa').data('pessoaId', id);

    // Preenche o campo de nome com o nome da pessoa
    document.getElementById('pessoaNome').value = nome;

    // Muda o título do modal para "Editar Pessoa"
    document.getElementById('modalPessoaLabel').textContent = 'Editar Pessoa';

    // Exibe o modal
    $('#modalPessoa').modal('show');

    // Passa o ID da pessoa para o botão de excluir no modal
    // $('#excluirPessoaBtn').off('click').on('click', function() {
    //     excluirPessoa(id);
    // });
}

function abrirModalCriar() {
    // Limpa os campos do modal para adicionar uma nova pessoa
    document.getElementById('pessoaNome').value = ''; // Limpa o campo de nome

    // Muda o título do modal para "Adicionar Pessoa"
    document.getElementById('modalPessoaLabel').textContent = 'Adicionar Pessoa';

    // Armazena o ID como undefined (não há ID para nova pessoa)
    $('#modalPessoa').data('pessoaId', undefined);

    // Exibe o modal
    $('#modalPessoa').modal('show');
}

function salvarPessoa() {
    // Obtém o ID da pessoa armazenado no modal usando data()
    const pessoaId = $('#modalPessoa').data('pessoaId');

    // Obtém o nome da pessoa
    const nome = document.getElementById('pessoaNome').value;

    if (!nome.trim()) {
        Swal.fire('Erro!', 'O campo nome é obrigatório.', 'error');
        return;
    }

    const url = pessoaId ? `/pessoas/${pessoaId}` : '/pessoas';  // Usando o ID armazenado no modal
    const method = pessoaId ? 'PUT' : 'POST';  // Se houver ID, usamos PUT (edição), caso contrário, POST (criação)

    // Faz a requisição para salvar ou editar a pessoa
    fetch(url, {
        method: method,
        headers: {
            'Content-Type': 'application/json', // Enviando JSON
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json' // Esperando JSON na resposta
        },
        body: JSON.stringify({ nome }) // Corpo da requisição
    })
        .then(response => {
            if (!response.ok) {
                throw new Error('Erro na resposta do servidor');
            }
            return response.json();
        })
        .then(data => {
            $('#modalPessoa').modal('hide');
            Swal.fire('Sucesso!', data.success || 'Pessoa salva com sucesso.', 'success');
            carregarPessoas();
        })
        .catch(error => {
            console.error('Erro ao salvar pessoa:', error);
            Swal.fire('Erro!', 'Não foi possível salvar a pessoa.', 'error');
        });
}


// Função para abrir o modal de substituição de responsáveis
function abrirModalSubstituirResponsavel(id) {
    // Armazenar o ID da pessoa no modal para saber qual pessoa está sendo excluída
    $('#modalSubstituirResponsavel').data('pessoaId', id);

    // Exibir o modal
    $('#modalSubstituirResponsavel').modal('show');
}

// Função para processar a substituição de responsáveis
$('#formSubstituirResponsavel').submit(function (e) {
    e.preventDefault();

    const pessoaId = $('#modalSubstituirResponsavel').data('pessoaId');
    const novoFiscal = $('#novoFiscal').val();
    const novoGestor = $('#novoGestor').val();
    const novoIntegrante = $('#novoIntegrante').val();

    // Enviar os dados para o backend para atualizar as licitações
    fetch(`/pessoas/${pessoaId}/substituir-responsavel`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            novo_fiscal: novoFiscal,
            novo_gestor: novoGestor,
            novo_integrante: novoIntegrante
        })
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                Swal.fire('Sucesso!', data.success, 'success');
                $('#modalSubstituirResponsavel').modal('hide');
                $('#pessoasTable').DataTable().ajax.reload(); // Recarregar a tabela após sucesso
            } else {
                Swal.fire('Erro!', data.error, 'error');
            }
        })
        .catch(error => {
            console.error('Erro ao substituir responsáveis:', error);
            Swal.fire('Erro!', 'Não foi possível substituir os responsáveis.', 'error');
        });
});


// function excluirPessoa(id) {
//     // Obtém o ID da pessoa armazenado no modal usando data()
//     // const pessoaId = $('#modalPessoa').data('pessoaId');  // Usando o data() para obter o ID

//     if (!id) {
//         console.error("ID da pessoa não encontrado.");
//         Swal.fire('Erro!', 'Não foi possível identificar a pessoa a ser excluída.', 'error');
//         return;
//     }

//     Swal.fire({
//         title: 'Tem certeza?',
//         text: 'Essa ação não pode ser desfeita!',
//         icon: 'warning',
//         showCancelButton: true,
//         confirmButtonText: 'Sim, excluir!',
//         cancelButtonText: 'Cancelar',
//         confirmButtonColor: '#d33',
//         cancelButtonColor: '#3085d6',
//     }).then((result) => {
//         if (result.isConfirmed) {
//             // Realiza a requisição AJAX para excluir a pessoa
//             fetch(`/pessoas/${id}`, {
//                 method: 'DELETE',
//                 headers: {
//                     'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
//                     'Accept': 'application/json'
//                 }
//             })
//                 .then(response => {
//                     if (!response.ok) {
//                         throw new Error('Erro na resposta do servidor');
//                     }
//                     return response.json();
//                 })
//                 .then(data => {
//                     Swal.fire('Excluído!', data.success || 'A pessoa foi excluída.', 'success');
//                     $('#pessoasTable').DataTable().ajax.reload(); // Recarrega a tabela do DataTables
//                 })
//                 .catch(error => {
//                     console.error('Erro ao excluir pessoa:', error);
//                     Swal.fire('Erro!', 'Não foi possível excluir a pessoa.', 'error');
//                 });
//         }
//     });
// }

function excluirPessoa(id) {
    if (!id) {
        console.error("ID da pessoa não encontrado.");
        Swal.fire('Erro!', 'Não foi possível identificar a pessoa a ser excluída.', 'error');
        return;
    }

    console.log("Excluindo pessoa com ID: ", id);  // Adicionando log para verificar o ID

    // Verificar se a pessoa está associada a licitações
    fetch(`/pessoas/${id}/verificar-associacao`)
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                // Se houver licitações associadas, mostrar o modal de substituição
                abrirModalSubstituirResponsavel(id);
            } else {
                // Se não houver, pode excluir a pessoa diretamente
                Swal.fire({
                    title: 'Tem certeza?',
                    text: 'Essa ação não pode ser desfeita!',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Sim, excluir!',
                    cancelButtonText: 'Cancelar',
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Realiza a requisição para excluir a pessoa
                        fetch(`/pessoas/${id}`, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                'Accept': 'application/json'
                            }
                        })
                            .then(response => response.json())
                            .then(data => {
                                Swal.fire('Excluído!', data.success || 'A pessoa foi excluída.', 'success');
                                $('#pessoasTable').DataTable().ajax.reload(); // Recarregar a tabela do DataTables
                            })
                            .catch(error => {
                                console.error('Erro ao excluir pessoa:', error);
                                Swal.fire('Erro!', 'Não foi possível excluir a pessoa.', 'error');
                            });
                    }
                });
            }
        })
        .catch(error => {
            console.error('Erro ao verificar associação:', error);
            Swal.fire('Erro!', 'Não foi possível verificar as associações da pessoa.', 'error');
        });
}

