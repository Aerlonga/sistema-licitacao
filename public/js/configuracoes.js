document.addEventListener('DOMContentLoaded', function () {
    carregarPessoas();

    // Submit do formulário do modal
    document.getElementById('formPessoa').addEventListener('submit', function (event) {
        event.preventDefault();
        salvarPessoa();
    });
});

function carregarPessoas() {
    const tabela = document.getElementById('pessoasTabela');
    if (!tabela) {
        console.error('Elemento "pessoasTabela" não encontrado no DOM.');
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

function abrirModalCriar() {
    document.getElementById('pessoaId').value = '';
    document.getElementById('pessoaNome').value = '';
    document.getElementById('modalPessoaLabel').textContent = 'Adicionar Pessoa';
    $('#modalPessoa').modal('show');
}

function abrirModalEditar(id, nome) {
    document.getElementById('pessoaId').value = id;
    document.getElementById('pessoaNome').value = nome;
    document.getElementById('modalPessoaLabel').textContent = 'Editar Pessoa';
    $('#modalPessoa').modal('show');
}

function salvarPessoa() {
    const id = document.getElementById('pessoaId').value;
    const nome = document.getElementById('pessoaNome').value;

    if (!nome.trim()) {
        Swal.fire('Erro!', 'O campo nome é obrigatório.', 'error');
        return;
    }

    const url = id ? `/pessoas/${id}` : '/pessoas';
    const method = id ? 'PUT' : 'POST';

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

function excluirPessoa(id) {
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
                    // Verifica se a resposta foi bem-sucedida
                    if (!response.ok) {
                        return response.json().then((data) => {
                            // Lança o erro para o catch caso seja um erro conhecido
                            throw new Error(data.error || 'Erro desconhecido.');
                        });
                    }
                    return response.json();
                })
                .then((data) => {
                    // Mostra mensagem de sucesso
                    Swal.fire('Sucesso!', data.success || 'Pessoa excluída com sucesso!.', 'success');
                    carregarPessoas();
                })
                .catch((error) => {
                    // Mostra mensagem de erro personalizada
                    Swal.fire('Erro!', error.message || 'Não foi possível inativar a pessoa.', 'error');
                });
        }
    });
}
