<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Service\UserService;

class RegisterController extends Controller
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function showRegistrationForm()
    {
        return view('register');
    }

    public function register(Request $request)
    {
        
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // Criação do usuário usando um serviço
        $user = $this->userService->createUser($validatedData);

        // Define a sessão para indicar que a conta foi criada
        session()->flash('account_created', true);

        // Redireciona de volta à página de registro
        return redirect()->route('register');
    }
}
