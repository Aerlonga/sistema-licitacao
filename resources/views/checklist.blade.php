@extends('layouts.adminlte.app')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/checklist.css') }}">
@endsection
@section('conteudo-pagina')
    <br>
    <div class="container">
        <h2 class="mb-4">Checklist de Documentos por Modalidade</h2>
        <div class="form-group">
            <label for="modalidadeSelect">Selecione a Modalidade:</label>
            <select id="modalidadeSelect" class="form-control">
                <option value="">Escolha uma Modalidade</option>
                <option value="adesao">Adesão</option>
                <option value="dispensa">Dispensa</option>
                <option value="inexigibilidade">Inexigibilidade</option>
                <option value="pregao">Pregão</option>
                <option value="pagamento">Pagamento</option>
                <option value="participe">Participe</option>
                <option value="renovacao">Renovação</option>
            </select>
        </div>
        <div id="checklistContainer" class="mt-4">
            <h4>Checklist de Documentos</h4>
            <ul id="checklist" class="list-group">
                <li class="list-group-item text-muted">Selecione uma modalidade para visualizar os documentos.</li>
            </ul>
        </div>
    </div>
    <br>
    <br>
@endsection


@section('scripts')
    <script src="{{ asset('js/checklist.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
@endsection
