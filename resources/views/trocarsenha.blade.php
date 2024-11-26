@extends('layouts.adminlte.styles')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/trocarsenha.css') }}">
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
                            placeholder="Digite seu email" required autofocus>
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-envelope"></span>
                            </div>
                        </div>
                    </div>

                    <div class="mb-4">
                        <div class="password-container">
                            <input type="password" name="password" id="password" class="form-control"
                                placeholder="Digite sua nova senha" required>
                            <span id="togglePassword" class="toggle-password-btn">
                                <i class="fas fa-eye"></i>
                            </span>
                        </div>
                    </div>

                    <div class="mb-4">
                        <div class="password-container">
                            <input type="password" name="password_confirmation" id="password_confirmation"
                                class="form-control" placeholder="Confirme sua nova senha" required>
                            <span id="togglePasswordConfirm" class="toggle-password-btn">
                                <i class="fas fa-eye"></i>
                            </span>
                        </div>
                    </div>

                    <div class="d-flex justify-content-center mt-2">
                        <button id="submitButton" type="submit" class="btn btn-primary w-100">
                            Redefinir Senha
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>


<script>
    document.getElementById('togglePassword').addEventListener('click', function() {
        togglePasswordVisibility('password', this);
    });

    document.getElementById('togglePasswordConfirm').addEventListener('click', function() {
        togglePasswordVisibility('password_confirmation', this);
    });


    function togglePasswordVisibility(inputId, toggleButton) {
        const passwordField = document.getElementById(inputId);
        const icon = toggleButton.querySelector('i');
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

    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('newPass');
        const submitButton = document.getElementById('submitButton');

        form.addEventListener('submit', function(e) {
            e.preventDefault(); // Impede o redirecionamento padrão do formulário

            submitButton.disabled = true;
            submitButton.textContent = 'Enviando...';

            const formData = new FormData(form); // Captura os dados do formulário

            fetch(form.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                    },
                })
                .then((response) => response.json())
                .then((data) => {
                    if (data.success) {
                        Swal.fire({
                            title: 'Sucesso!',
                            text: 'Sua senha foi redefinida com sucesso.',
                            icon: 'success',
                            confirmButtonText: 'OK',
                        }).then(() => {
                            window.location.href =
                            '/login'; // Redireciona para a página de login
                        });
                    } else {
                        throw new Error(data.message || 'Erro ao redefinir senha.');
                    }
                })
                .catch((error) => {
                    Swal.fire({
                        title: 'Erro!',
                        text: error.message,
                        icon: 'error',
                        confirmButtonText: 'OK',
                    });
                })
                .finally(() => {
                    submitButton.disabled = false;
                    submitButton.textContent = 'Redefinir Senha';
                });
        });
    });
</script>

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endsection


{{-- document.addEventListener('DOMContentLoaded', function() {
        const submitButton = document.querySelector('#submitButton');

        document.getElementById('newPass').addEventListener('submit', function() {
            const submitButton = document.getElementById('submitButton');
            submitButton.disabled = true;
            submitButton.textContent = 'Enviando...';
        });


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
    }); --}}
