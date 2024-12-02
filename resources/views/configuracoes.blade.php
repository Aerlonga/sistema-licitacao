@extends('layouts.adminlte.app')

@section('titulo-pagina')
    Configurações do Sistema
@endsection

@section('conteudo-pagina')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <h1 class="mb-4 mt-2">Configurações</h1>
                <p>Bem-vindo à área de configurações. Apenas administradores podem acessar esta página.</p>
                <div class="card card-default">
                    <div class="card-body table-responsive">
                        <!-- Tabela de pessoas -->
                        <table id="pessoasTable" class="table table-bordered table-striped w-100" data-url="/pessoas">
                            <thead>
                                <tr>
                                    <th>Nome</th>
                                    <th>Sobrenome</th>
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

    {{-- inicio do modal --}}

    <div class="modal fade" id="modalPessoa" tabindex="-1" aria-labelledby="modalPessoaLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalPessoaLabel">Adicionar Pessoa</h5>
                </div>
                <div class="modal-body">
                    <form id="formPessoa">
                        @csrf
                        <input type="hidden" name="id" id="pessoaId">
                        <div class="mb-3">
                            <label for="pessoaNome" class="form-label">Nome</label>
                            <input type="text" class="form-control" id="pessoaNome" name="nome" required>
                        </div>
                        <div class="mb-3">
                            <label for="pessoaSobrenome" class="form-label">Sobrenome</label>
                            <input type="text" class="form-control" id="pessoaSobrenome" name="sobrenome" required>
                        </div>
                    </form>
                </div>
                <div class="modal-footer d-flex justify-content-between">
                    <button type="button" class="btn btn-danger" id="excluirPessoaButton" onclick="excluirPessoaModal()"><i
                            class="fas fa-trash"></i> Excluir</button>
                    <div>
                        <button type="button" class="btn btn-primary" onclick="salvarPessoa()">Salvar</button>
                        <button type="button" class="btn btn-light" data-dismiss="modal">Fechar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('js/configuracoes.js') }}"></script>
@endsection
