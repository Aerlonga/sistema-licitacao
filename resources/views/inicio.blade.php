@extends('layouts.adminlte.app')

@section('titulo-pagina')
    
@endsection

@section('conteudo-pagina')
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-12 text-center">
                <h1>Bem-vindo ao Sistema de Contratações</h1>
                <p class="lead mt-3">Este é o sistema de controle e gerenciamento de contratações. Utilize o menu lateral para acessar as funcionalidades do sistema.</p>
                <p class="mt-4">
                    <a href="{{ route('EquipeSalva') }}" class="btn btn-secondary">Gerar Equipe</a>
                    <a href="{{ route('visualizarLicitacoes') }}" class="btn btn-secondary">Acessar Contratações</a>
                </p>
            </div>
        </div>
    </div>
@endsection
