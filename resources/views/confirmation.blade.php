@extends('layouts.adminlte.styles')

<div class="d-flex justify-content-center align-items-center vh-100">
    <div class="alert alert-success">
        <p>Conta criada com sucesso!</p>
        <a href="{{ route('login') }}" class="btn btn-primary">Ir para Login</a>
    </div>
</div>
