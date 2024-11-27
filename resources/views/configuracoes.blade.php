@extends('layouts.adminlte.app')

@section('titulo-pagina')
    Configurações do Sistema
@endsection

@section('conteudo-pagina')
    <div class="container">
        <h1>Configurações</h1>
        <p>Bem-vindo à área de configurações. Apenas administradores podem acessar esta página.</p>

        <!-- Botão para adicionar nova pessoa -->
        <button class="btn btn-primary mb-3" onclick="abrirModalCriar()">Adicionar Pessoa</button>

        <!-- Tabela de pessoas -->
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nome</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody id="pessoasTabela">
                @foreach ($pessoas as $pessoa)
                    <tr>
                        <td>{{ $pessoa->id_pessoa }}</td>
                        <td>{{ $pessoa->nome }}</td>
                        <td>
                            <button class="btn btn-warning btn-sm"
                                onclick="abrirModalEditar({{ $pessoa->id_pessoa }}, '{{ $pessoa->nome }}')">Editar</button>
                            <form action="{{ route('pessoas.destroy', $pessoa->id_pessoa) }}" method="POST"
                                style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">Excluir</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Modal para criar/editar pessoa -->
    <div class="modal fade" id="modalPessoa" tabindex="-1" aria-labelledby="modalPessoaLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalPessoaLabel">Adicionar Pessoa</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="formPessoa" action="{{ route('pessoas.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="id" id="pessoaId">
                        <div class="mb-3">
                            <label for="pessoaNome" class="form-label">Nome</label>
                            <input type="text" class="form-control" id="pessoaNome" name="nome" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Salvar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('js/configuracoes.js') }}"></script>
@endsection
