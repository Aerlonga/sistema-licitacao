<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Redefinir Senha</title>
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
</head>
<body>
    <div class="reset-password-container">
        <h2>Redefinir Senha</h2>
        @if (session('status'))
            <div>
                {{ session('status') }}
            </div>
        @endif
        <form action="{{ route('password.update') }}" method="POST">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">

            <label for="email">Email</label>
            <input type="email" name="email" value="{{ old('email') }}" required autofocus>

            <label for="password">Nova Senha</label>
            <input type="password" name="password" required>

            <label for="password_confirmation">Confirme a Nova Senha</label>
            <input type="password" name="password_confirmation" required>

            <button type="submit">Redefinir Senha</button>
        </form>
    </div>
</body>
</html>
