<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class Host
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if (auth()->user()->role == 'host' || auth()->user()->role == 'admin') {
            return $next($request);
        } else {
            return back()->with('error', 'Page not found()');
        }
    }
}
