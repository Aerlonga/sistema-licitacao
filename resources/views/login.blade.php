<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Licitações - Login</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.1/dist/css/adminlte.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
</head>

<body>
    <div class="login-container">
        <div class="login-image">
            <img src="{{ asset('imagens/imagemlogin.png') }}" alt="Imagem de Login">
        </div>
        <div class="login-form">

            <img src="{{ asset('imagens/imagemlogo.png') }}" alt="Logo Sistema de Licitação">
            <h2>Sistema de Licitação</h2>

            <form action="{{ route('login') }}" method="POST">
                @csrf
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required>

                <label for="password">Senha</label>
                <div class="password-container">
                    <input type="password" id="password" name="password" required>
                    <button type="button" id="togglePasswordVisibility" class="toggle-password-btn">
                        <i class="fas fa-eye"></i>
                    </button>
                </div>


                <div class="form-check">
                    <input type="checkbox" class="form-check-input" id="exampleCheck2">
                    <label class="form-check-label" for="exampleCheck2">Remember me</label>
                </div>


                <button type="submit" class="login-button">Entrar</button>

                <div class="extra-links mt-3 text-center">
                    <a href="{{ route('register') }}" class="btn btn-link">Criar Conta</a>
                    <a href="{{ route('password.request') }}" class="btn btn-link">Esqueci minha Senha</a>
                </div>
            </form>

        </div>
    </div>

    <script src="{{ asset('js/login.js') }}"></script>
</body>

</html>
