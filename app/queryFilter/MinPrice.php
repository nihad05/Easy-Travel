<?php

namespace App\queryFilter;

use Closure;

class MinPrice
{
    public function handle($request, Closure $next)
    {
        $price = request()->get('minPrice');
        if ($price == null || $price < 0) {
            return $next($request);
        }
        $bulider = $next($request);

        return $bulider->whereBetween('price', [$price, 1000000000000000]);

    }
}
