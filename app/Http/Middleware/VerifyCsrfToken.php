<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;
use Illuminate\Session\TokenMismatchException;
use Illuminate\Support\Facades\Log;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
     'login',
    ];
    public function handle($request, Closure $next)
    {
        try {
            return parent::handle($request, $next);
        } catch (TokenMismatchException $e) {
            Log::error('CSRF exception', [
                'session' => $request->session()->all(),
                'cookie' => $request->cookie(),
                'session_token' => $request->session()->token(),
                'request_token' => $this->getTokenFromRequest($request),
                'request_ip' => $request->ip(),
                'request_path' => $request->path(),
                'user_agent' => $request->userAgent(),
                // any other data you need
            ]);

            throw $e;
        }
    }
}
