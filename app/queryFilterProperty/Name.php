<?php

namespace App\queryFilterProperty;

use Closure;

class Name
{
    public function handle($request, Closure $next)
    {
        $name = request()->get('name');
        if ($name == null) {
            return $next($request);
        }
        $builder = $next($request);

        return $builder->where('name', 'like', '%'.$name.'%');
    }
}
