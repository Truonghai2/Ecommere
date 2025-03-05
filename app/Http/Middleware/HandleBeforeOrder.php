<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class HandleBeforeOrder
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();

        if($user->phone_number == null){
            return redirect()->back()->withErrors([
                'error' => "Vui lòng thêm số điện thoại trước khi đặt hàng"
            ]);
        }
        if ($user->verify_number == 0) {
            session(['url.intended' => url()->current()]);

            return response()->json(["url" => route('verify.phone')]);
        }
        return $next($request);
    }
}
