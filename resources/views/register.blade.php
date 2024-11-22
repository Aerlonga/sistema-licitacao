@extends('layouts.adminlte.styles')

<div class="d-flex justify-content-center align-items-center vh-100">
    <div class="register-box">
        <div class="register-logo">
            <b>Sistema de Licitações</b>
        </div>

        <div class="card">
            <div class="card-body register-card-body">
                <p class="login-box-msg">Faça seu cadastro</p>

                <!-- Adicione a exibição de erros aqui -->
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('register') }}" method="POST">
                    @csrf
                    <div class="input-group mb-3">
                        <input type="text" name="name" class="form-control" placeholder="Nome Completo"
                            value="{{ old('name') }}" required>
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-user"></span>
                            </div>
                        </div>
                    </div>
                    <div class="input-group mb-3">
                        <input type="email" name="email" class="form-control" placeholder="Email"
                            value="{{ old('email') }}" required>
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-envelope"></span>
                            </div>
                        </div>
                    </div>
                    <div class="input-group mb-3">
                        <input type="password" name="password" class="form-control" placeholder="Senha" required>
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div>
                    </div>
                    <div class="input-group mb-3">
                        <input type="password" name="password_confirmation" class="form-control"
                            placeholder="Confirme a Senha" required>
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 d-flex justify-content-center mt-2">
                            <button type="submit" class="btn btn-primary">Criar Conta</button>
                        </div>
                    </div>
                </form>
            </div>
            <!-- /.form-box -->
        </div><!-- /.card -->
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Verifica se a mensagem de conta criada foi configurada na sessão e limpa a sessão imediatamente
        @if (session()->pull('account_created', false))
            Swal.fire({
                title: 'Conta criada com sucesso!',
                text: 'Clique em "OK" para ser redirecionado ao login.',
                icon: 'success',
                confirmButtonText: 'OK'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = '/login';
                }
            });
        @endif
    });
</script>


@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endsection
