@extends('layouts.adminlte.styles')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/esqueciminhasenha.css') }}">
@endsection

<div class="d-flex justify-content-center align-items-center vh-100">
    <div class="register-box">
        <div class="register-logo">
            <b>Redefinição de Senha</b>
        </div>
            <div class="card">
                <div class="card-body register-card-body">
                    <p class="login-box-msg">Informe seu email para criar uma nova senha</p>
                    <form id="resetForm" action="{{ route('password.email') }}" method="POST">
                        @csrf
                        <div class="input-group mb-3">
                            <input type="email" name="email" class="form-control" placeholder="Email" required>
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-envelope"></span>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12 d-flex justify-content-center mt-2">
                                <button id="submitButton" type="submit" class="btn btn-primary">Confirmar</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const submitButton = document.querySelector('#submitButton');

        // Desativa o botão e altera o texto enquanto o formulário está sendo enviado
        const form = document.querySelector('#resetForm');
        form.addEventListener('submit', function() {
            submitButton.disabled = true;
            submitButton.textContent = 'Enviando...';
        });

        // Exibe mensagens após o redirecionamento
        @if (session('status'))
            Swal.fire({
                title: 'Email enviado!',
                icon: 'success',
                confirmButtonText: 'OK',
                confirmButtonColor: '#3085d6',
            });
        @endif

        @if ($errors->any())
            Swal.fire({
                title: 'Erro!',
                text: '{{ $errors->first('email') }}',
                icon: 'error',
                confirmButtonText: 'OK',
                confirmButtonColor: '#d33',
            });
        @endif
    });
</script>

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endsection
