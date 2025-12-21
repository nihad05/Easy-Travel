<?php

namespace App\queryFilterProperty;

use Closure;

class Location
{
    public function handle($request, Closure $next)
    {
        $location = request()->get('location');
        if ($location == null) {
            return $next($request);
        }
        $builder = $next($request);

        return $builder->where('location', 'like', '%'.$location.'%');
    }
}
