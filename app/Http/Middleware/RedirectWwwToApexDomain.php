<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RedirectWwwToApexDomain
{
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->getHost() === 'www.autoiq.rs') {
            return new RedirectResponse('https://autoiq.rs'.$request->getRequestUri(), 301);
        }

        return $next($request);
    }
}
