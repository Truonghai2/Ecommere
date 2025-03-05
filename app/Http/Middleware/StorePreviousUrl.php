<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class StorePreviousUrl
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Lưu lại URL trước đó
        if ($request->method() === 'GET' && !$request->ajax()) {
            Session::put('previous_url', url()->previous());
        }

        return $next($request);
    }
}
