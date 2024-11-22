@extends('layouts.adminlte.app')

@section('titulo-pagina')
    Gerar Equipe
@endsection

@section('conteudo-pagina')
    <h1>Gerar Equipe</h1>
    <br>
    <form action="{{ route('LicitacoesSalvar') }}" method="POST" id="gerarEquipeForm"
        data-url="{{ route('LicitacoesSalvar') }}">
        @csrf

        <div class="card">
            <div class="card-header bg-primary bg-secondary">Preencha as Informações abaixo</div>

            <div class="card-body">
                <p class="text-right text-danger">* Preenchimento Obrigatório</p>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <strong class="texto-cinza-padrao">Objeto da Contratação:*</strong>
                            <input type="text" id="objeto_contratacao" value="{{ old('objeto_contratacao') }}"
                                class="form-control fonte-padrao" name="objeto_contratacao"
                                placeholder="Objeto da Contratação:">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <strong class="texto-cinza-padrao">SEI:*</strong>
                            <input type="text" id="sei" value="{{ old('sei') }}""
                                class="form-control fonte-padrao" name="sei" placeholder="SEI:">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <strong class="texto-cinza-padrao">SISLOG:*</strong>
                            <input type="text" id="sislog" value="{{ old('sislog') }}"
                                class="form-control fonte-padrao" name="sislog" placeholder="SISLOG:">
                        </div>
                    </div>
                </div>


                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <strong class="texto-cinza-padrao">Modalidade:*</strong>
                            <input type="text" id="modalidade" value="{{ old('modalidade') }}"
                                class="form-control fonte-padrao" name="modalidade" placeholder="Modalidade:">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <strong class="texto-cinza-padrao">Situação:*</strong>
                            <select id="situacao" name="situacao" class="form-control fonte-padrao">
                                <option value="Selecione">Selecione</option>
                                <option value="Em andamento">Em andamento</option>
                                <option value="Em outro setor">Em outro setor</option>
                                <option value="Finalizado">Finalizado</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <strong class="texto-cinza-padrao">Local:*</strong>
                            <select id="local" name="local" class="form-control fonte-padrao">
                                <option value="Selecione">Selecione</option>
                                <option value="TR e/ou ETP">TR e/ou ETP</option>
                                <option value="GELIC e GEORC">GELIC e GEORC</option>
                                <option value="GELIC">GELIC</option>
                                <option value="GEORC">GEORC</option>
                                <option value="PROSET">PROSET</option>
                                <option value="PR">PR</option>
                                <option value="CACTIC">CACTIC</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12 text-right">
                        <button id="gerarEquipeBtn" type="submit" class="btn btn-primary">Gerar Equipe</button>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                Preencha todos os campos obrigatórios antes de clicar em 'Gerar Equipe'
            </div>
        </div>

    </form>


    {{-- Exibir mensagens de erro --}}
    @if ($errors->any())
        <div class="alert alert-danger mt-3">
            @foreach ($errors->all() as $error)
                {{ $error }}<br>
            @endforeach
        </div>
    @endif

    @if (session('success'))
        <div class="alert alert-success mt-3">
            {{ session('success') }}
        </div>
    @endif

    <div id="resultado" style="margin-top: 20px;">
    </div>
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ asset('js/equipe.js') }}"></script>
@endsection

