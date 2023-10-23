<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;
use Illuminate\Session\TokenMismatchException;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array<int, string>
     */
    protected $except = [
        //
    ];

    public function handle($request, Closure $next){
        try {
            return parent::handle($request, $next);
        } catch (TokenMismatchException $e) {
            // ? Token CSRF caducado, regenera el token y permite la solicitud
            $request->session()->regenerateToken();
            return $this->addCookieToResponse($request, $next($request));
        }
    }
}
