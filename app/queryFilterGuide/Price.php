<?php

namespace App\queryFilterGuide;

use Closure;

class Price
{
    public function handle($request, Closure $next)
    {
        $minPrice = request()->get('minPrice');
        $maxPrice = request()->get('maxPrice');
        $builder = $next($request);
        if ($minPrice != null && $maxPrice != null) {
            return $builder->with('guides')->whereBetween('price', [$minPrice, $maxPrice]);
        } elseif ($minPrice != null && $maxPrice == null) {
            return $builder->with('guides')->whereBetween('price', [$minPrice, 10000000]);
        } elseif ($maxPrice != null && $minPrice == null) {
            return $builder->with('guides')->whereBetween('price', ['<=', $maxPrice]);
        } else {

        }
    }
}
