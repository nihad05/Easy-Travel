<?php

namespace App\queryFilter;

use Closure;

class Location
{
    public function handle($request, Closure $next)
    {
        $name = request()->get('location');
        if ($name == null) {
            return $next($request);
        }
        $bulider = $next($request);

        return $bulider->where('location', 'like', '%'.$name.'%');

    }
}
