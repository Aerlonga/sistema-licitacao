@extends('layouts.adminlte.app')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/licitacoes.css') }}">
@endsection

{{-- @section('titulo-pagina')

@endsection --}}

@section('conteudo-pagina')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <h2 class="mb-4 mt-2">Lista de Licitações</h2>
                <div class="card card-default">
                    <div class="card-body table-responsive">
                        <form id="licitacoesForm" action="{{ route('listarDatatable') }}" method="POST">
                            @csrf
                            <table id="licitacoesTable" class="table table-bordered table-striped w-100"
                                data-url="{{ route('listarDatatable') }}">
                                <thead>
                                    <tr>
                                        <th>SEI</th>
                                        <th>SISLOG</th>
                                        <th>Modalidade</th>
                                        <th>Objeto</th>
                                        <th>Gestor</th>
                                        <th>Integrante</th>
                                        <th>Fiscal</th>
                                        <th>Data de inclusão</th>
                                        <th>Situação</th>
                                        <th>Local</th>
                                        <th>Ação</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Conteúdo será carregado dinamicamente pelo DataTables -->
                                </tbody>
                                <tfoot>
                                    <tr>
                                        {{-- <td colspan="10" class="text-right"> <!-- Ajuste a contagem de colunas -->
                                            <button type="button" class="btn btn-primary"
                                                onclick="redirecionarParaListar()">Editar</button>
                                        </td> --}}
                                    </tr>
                                </tfoot>

                            </table>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

{{-- inicio do modal --}}

<div class="modal fade" id="visualizarLicitacaoModal" tabindex="-1" role="dialog"
    aria-labelledby="visualizarLicitacaoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table id="tableVisualizarLicitacao" class="table table-striped">
                    <tbody>
                        <tr>
                            <td><b>Modalidade:</b></td>
                            <td id="modalidadeText"></td>
                        </tr>
                        <tr>
                            <td><b>Objeto:</b></td>
                            <td id="objetoText"></td>
                        </tr>
                        <tr>
                            <td><b>Gestor:</b></td>
                            <td id="gestorText"></td>
                        </tr>
                        <tr>
                            <td><b>Integrante:</b></td>
                            <td id="integranteText"></td>
                        </tr>
                        <tr>
                            <td><b>Fiscal:</b></td>
                            <td id="fiscalText"></td>
                        </tr>
                        <tr>
                            <td><b>Data de Inclusão:</b></td>
                            <td id="dataInclusaoText"></td>
                        </tr>
                        <tr>
                            <td><b>SEI:</b></td>
                            <td id="seiText"></td>
                        </tr>
                        <tr>
                            <td><b>SISLOG:</b></td>
                            <td id="sislogText"></td>
                        </tr>
                        <tr>
                            <td><b>Situação:</b></td>
                            <td id="situacaoText"></td>
                        </tr>
                        <tr>
                            <td><b>Local:</b></td>
                            <td id="localText"></td>
                        </tr>
                        <tr>
                            <td><b>Observação:</b></td>
                            <td id="observacaoText"></td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="modal-footer d-flex justify-content-between">
                <button type="button" class="btn btn-light"
                    onclick="excluirLicitacao($('#visualizarLicitacaoModal').data('licitacaoId'))" title="Excluir">
                    <i class="fas fa-trash"></i> Excluir
                </button>
                <div>
                    <button type="button" class="btn btn-primary" onclick="salvarEdicao()"
                        id="salvarButton">Salvar</button>
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Fechar</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    const isAdmin = @json(Auth::check() && Auth::user()->isAdmin());
</script>


@section('scripts')
    <script src="{{ asset('js/licitacao.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
@endsection
