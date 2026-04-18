<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureRole
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();

        abort_unless($user, 403);

        if (! in_array($user->role->value, $roles, true)) {
            abort(403, 'Nemate dozvolu za ovu sekciju.');
        }

        return $next($request);
    }
}
