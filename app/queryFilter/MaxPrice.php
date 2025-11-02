<?php

namespace App\queryFilter;

use Closure;

class MaxPrice
{
    public function handle($request, Closure $next)
    {
        $price = request()->get('maxPrice');
        if ($price == null || $price < 0) {
            return $next($request);
        }
        $bulider = $next($request);

        return $bulider->whereBetween('price', [0, $price]);

    }
}
