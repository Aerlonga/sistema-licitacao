<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;


class CheckRole
{

    public function handle($request, Closure $next, $role)
    {

        if (!Auth::check() || Auth::user()->role !== $role) {
            Log::warning('Acesso negado no middleware CheckRole', [
                'user_id' => Auth::id(),
                'user_role' => Auth::user()->role ?? 'não autenticado',
                'required_role' => $role,
            ]);

            return redirect('/'); // Redireciona ou mostra mensagem de erro
        }

        return $next($request);
    }
}
