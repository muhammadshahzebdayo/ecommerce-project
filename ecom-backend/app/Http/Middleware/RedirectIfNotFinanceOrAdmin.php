<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfNotFinanceOrAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();
        if ($user && !in_array($user->role, ['admin', 'finance'])) {
            abort(403, 'Unauthorized access to Finance Panel.');
        }

        return $next($request);
    }
}
