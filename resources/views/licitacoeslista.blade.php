@extends('layouts.adminlte.app')

@section('titulo-pagina')
    CHECKLIST
@endsection

@section('conteudo-pagina')

    CHECK LIST A SER ADICIONADO

    @if (session('success'))
        <div class="alert alert-success mt-3">{{ session('success') }}</div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger mt-3">{{ session('error') }}</div>
    @endif
@endsection

@section('scripts')
    <script src="{{ asset('js/licitacoeslista.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
@endsection
