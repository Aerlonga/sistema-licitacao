<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    public function getAllUsers()
    {
        try {
            $users = User::all(['id', 'name', 'email', 'role']);
            return response()->json($users);
        } catch (\Exception $e) {
            Log::error('Erro ao buscar usuários:', ['message' => $e->getMessage()]);
            return response()->json(['error' => 'Erro ao buscar usuários.'], 500);
        }
    }

    public function alterarRole(Request $request, $id)
    {
        $request->validate([
            'role' => 'required|in:user,admin',
        ]);

        try {
            $user = User::findOrFail($id);
            $user->role = $request->role;
            $user->save();

            return response()->json(['success' => 'Papel do usuário atualizado com sucesso.']);
        } catch (\Exception $e) {
            Log::error('Erro ao alterar o papel do usuário:', ['message' => $e->getMessage()]);
            return response()->json(['error' => 'Erro ao alterar o papel do usuário.'], 500);
        }
    }
}
