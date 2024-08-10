<?php

namespace App\Http\Middleware;

use App\Traits\ResponseNormalizer;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EmailVerificationMiddleware
{
    use ResponseNormalizer;
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (
            !auth()->user() ||
            !auth()->user()->email_verified_at
        ) {
            return $this->forbidden(
                message: 'Your email address is not verified.'
            );
        }
        return $next($request);
    }
}