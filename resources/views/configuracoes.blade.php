{{-- @extends('layouts.adminlte.app')

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
@endsection --}}


@extends('layouts.adminlte.app')

@section('titulo-pagina')
    Configurações do Sistema
@endsection

@section('conteudo-pagina')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <h2 class="mb-4 mt-2">Configurações</h2>
                <div class="card card-default">
                    <div class="card-body table-responsive">
                        <p>Bem-vindo à área de configurações. Apenas administradores podem acessar esta página.</p>

                        <!-- Botão para adicionar nova pessoa -->
                        <button type="button" class="btn btn-primary mb-3" onclick="abrirModalCriar()">Adicionar
                            Pessoa</button>

                        <!-- Tabela de pessoas -->
                        <table id="pessoasTable" class="table table-bordered table-striped w-100">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Nome</th>
                                    <th>Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Conteúdo será carregado dinamicamente pelo DataTables -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

<!-- Modal para criar/editar pessoa -->
<div class="modal fade" id="modalPessoa" tabindex="-1" aria-labelledby="modalPessoaLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalPessoaLabel">Adicionar Pessoa</h5>
            </div>
            <div class="modal-body">
                <form id="formPessoa" action="{{ route('pessoas.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="id" id="pessoaId">
                    <div class="mb-3">
                        <label for="pessoaNome" class="form-label">Nome</label>
                        <input type="text" class="form-control" id="pessoaNome" name="nome" required>
                    </div>
                    <div class="modal-footer d-flex justify-content-between">
                        <button type="button" class="btn btn-light" id="excluirPessoaBtn"
                            onclick="abrirModalTrocarResponsavel()">
                            <i class="fas fa-trash"></i> Excluir
                        </button>
                        <div>
                            <button type="button" class="btn btn-primary" onclick="salvarPessoa()">Salvar</button>
                            <button type="button" class="btn btn-danger" data-dismiss="modal">Fechar</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal para substituir responsável -->
<div class="modal fade" id="modalTrocarResponsavel" tabindex="-1" aria-labelledby="modalTrocarResponsavelLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTrocarResponsavelLabel">Substitua os responsáveis</h5>
            </div>
            <div class="modal-body">
                <form id="formTrocarResponsavel">
                    <div class="mb-3">
                        <label for="novoFiscal" class="form-label">Novo Fiscal</label>
                        <select class="form-control" id="novoFiscal" name="novoFiscal" required>
                            <option value="">Selecione um novo fiscal</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="novoGestor" class="form-label">Novo Gestor</label>
                        <select class="form-control" id="novoGestor" name="novoGestor" required>
                            <option value="">Selecione um novo gestor</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="novoIntegrante" class="form-label">Novo Integrante</label>
                        <select class="form-control" id="novoIntegrante" name="novoIntegrante" required>
                            <option value="">Selecione um novo integrante</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Salvar</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                </form>
            </div>
        </div>
    </div>
</div>

@section('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
    <script src="{{ asset('js/configuracoes.js') }}"></script>
@endsection
