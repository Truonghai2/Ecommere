<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Auth\Middleware\Authenticate as Middleware;

class CheckRole extends Middleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string[]  ...$guards
     * @return mixed
     */
    public function handle($request, Closure $next, ...$guards)
    {
        // Check if the user is verified
        if ($this->isVerified()) {
            // If the user is verified, allow the request to continue
            return $next($request);
        }

        // If the user is not verified, redirect to the home page with an error message
        return redirect('/')->with('error', 'Unauthorized access.');
    }

    /**
     * Check if the authenticated user is verified.
     *
     * @return bool
     */
    protected function isVerified()
    {
        // Assuming you have a 'verified' column in the users table
        return auth()->check() && auth()->user()->role == 1;
    }

}
