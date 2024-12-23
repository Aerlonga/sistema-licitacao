<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LicitacaoController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\PessoaController;

Route::get('/', function () {
    return redirect('/login');
});

// Rotas de autenticação (acessíveis a usuários não autenticados)
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Rotas de registro (Criar Conta)
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

// Rotas para solicitação e redefinição de senha (Esqueci minha Senha)
Route::get('/password/reset', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('/password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('/password/reset/{token}', [ForgotPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('/password/reset', [ForgotPasswordController::class, 'reset'])->name('password.update');

// Rotas protegidas pelo middleware 'auth'
Route::middleware(['auth'])->group(function () {
    // Todas as rotas definidas dentro deste grupo só podem ser acessadas por usuários autenticados.

    Route::get('/checklist', [LicitacaoController::class, 'checklist'])->name('checklist');

    Route::get('/licitacoes/{id}/show', [LicitacaoController::class, 'show'])->name('licitacoes.show');

    Route::get('/inicio', [LicitacaoController::class, 'inicio'])->name('inicio');
    Route::get('/licitacoes', [LicitacaoController::class, 'visualizarLicitacoes'])->name('visualizarLicitacoes');

    Route::get('/equipe', function () {
        return view('equipe');
    })->name('EquipeSalva');

    Route::get('/contato', function () {
        return view('contato');
    })->name('contato');

    // Rotas protegidas para administradores
    Route::middleware(['role:admin'])->group(function () {
        Route::get('/configuracoes', [PessoaController::class, 'index'])->name('configuracoes'); // View de configurações
        Route::get('/pessoas', [PessoaController::class, 'getAll'])->name('pessoas.index'); // API para listar pessoas
        Route::post('/pessoas', [PessoaController::class, 'store'])->name('pessoas.store'); // Criar pessoa
        Route::put('/pessoas/{id}', [PessoaController::class, 'update'])->name('pessoas.update'); // Atualizar pessoa
        Route::delete('/pessoas/{id}', [PessoaController::class, 'destroy'])->name('pessoas.destroy'); // Excluir pessoa
    });    

    Route::post('/gerar-equipe', [LicitacaoController::class, 'gerarEquipe'])->name('LicitacoesSalvar');
    Route::get('/listarLicitacoes', [LicitacaoController::class, 'listarLicitacoes'])->name('listarLicitacoes');
    Route::get('/licitacoes-datatable', [LicitacaoController::class, 'listarDatatable'])->name('listarDatatable');

    Route::put('/licitacoes/{id}', [LicitacaoController::class, 'update'])->name('licitacoes.update');
    Route::delete('/licitacoes/{id}', [LicitacaoController::class, 'destroy'])->name('licitacoes.destroy');
    Route::get('/licitacoes/{id}/show', [LicitacaoController::class, 'show'])->name('licitacoes.show');
});
