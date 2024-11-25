@extends('layouts.adminlte.styles')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
@endsection

<div class="d-flex justify-content-center align-items-center vh-100">
    <div class="register-box">
        <div class="register-logo">
            <b>Redefinir Senha</b>
        </div>
        <div class="card">
            <div class="card-body register-card-body">

                <p class="login-box-msg">Informe seu e-mail e a nova senha.</p>

                @if (session('status'))
                    <div class="alert alert-success">
                        {{ session('status') }}
                    </div>
                @endif

                <form id="newPass" action="{{ route('password.update') }}" method="POST">
                    @csrf
                    <input type="hidden" name="token" value="{{ $token }}">

                    <div class="input-group mb-4">
                        <input type="email" name="email" value="{{ old('email') }}" class="form-control"
                            placeholder="Email" required autofocus>
                        <div class="input-group-text">
                            <span class="fas fa-envelope"></span>
                        </div>
                    </div>

                    <div class="input-group mb-4 position-relative">
                        <input type="password" name="password" id="password" class="form-control"
                            placeholder="Nova Senha" required>
                        <button type="button" id="togglePassword" class="toggle-password-btn position-absolute">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>

                    <div class="input-group mb-4 position-relative">
                        <input type="password" name="password_confirmation" id="password_confirmation"
                            class="form-control" placeholder="Confirme a Senha" required>
                        <button type="button" id="togglePasswordConfirm" class="toggle-password-btn position-absolute">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>


                    <div class="row">
                        <div class="col-12 d-flex justify-content-center mt-2">
                            <button id="submitButton" type="submit" class="btn btn-primary">
                                Redefinir Senha
                            </button>
                        </div>
                    </div>

                </form>

            </div>
        </div>
    </div>
</div>

<script>
    // Função para alternar visibilidade de senha
    function togglePasswordVisibility(inputId, button) {
        const passwordField = document.getElementById(inputId);
        const icon = button.querySelector('i');
        if (passwordField.type === 'password') {
            passwordField.type = 'text';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            passwordField.type = 'password';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    }

    document.getElementById('togglePassword').addEventListener('click', function() {
        togglePasswordVisibility('password', this);
    });

    document.getElementById('togglePasswordConfirm').addEventListener('click', function() {
        togglePasswordVisibility('password_confirmation', this);
    });

    document.addEventListener('DOMContentLoaded', function() {
        const submitButton = document.querySelector('#submitButton');

        // Desativa o botão enquanto o formulário está sendo enviado
        const form = document.querySelector('#newPass');
        form.addEventListener('submit', function() {
            submitButton.disabled = true;
            submitButton.textContent = 'Enviando...';
        });

        // Exibe mensagens do Laravel com SweetAlert
        @if (session('status'))
            Swal.fire({
                title: 'Sucesso!',
                text: "{{ session('status') }}",
                icon: 'success',
                confirmButtonText: 'OK',
                confirmButtonColor: '#3085d6',
            });
        @endif

        @if ($errors->any())
            Swal.fire({
                title: 'Erro!',
                text: '{{ $errors->first() }}',
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
