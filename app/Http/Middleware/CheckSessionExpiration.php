<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckSessionExpiration
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if the user is authenticated
        if (Auth::check()) {
            // Check if the session has expired
            if (time() - session('last_activity') > config('session.lifetime') * 60) {
                // The session has expired, log the user out and redirect to the login page
                Auth::logout();
                return redirect('/');
            }
        }

        // Update the last activity timestamp
        session(['last_activity' => time()]);

        return $next($request);
    }
}
