<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckCode
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
        $code = 'DFA5456F786AS0asdc0A';
        if ($request->header('x_code')
            && $request->header('x_code') === $code) {
            return $next($request);
        } else {
            return back();
        }
    }
}
