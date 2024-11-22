<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    // Exibir o formulário de login
    public function showLoginForm()
    {
        return view('login'); // Certifique-se de que a view 'login' existe
    }

    // Método para realizar o login
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');
        $remember = $request->has('remember'); // Verifica se "remember" foi marcado

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();
            return redirect()->intended('inicio'); // Redireciona para a página desejada após login
        }

        return back()->withErrors([
            'email' => 'Credenciais inválidas.',
        ]);
    }


    // Método para realizar o logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login'); // Redireciona para login após logout
    }
}
