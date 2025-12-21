<?php

namespace App\queryFilterProperty;

class MinPrice
{
    public function handle($request, $next)
    {
        $price = request()->get('minPrice');
        $maxPrice = request()->get('maxPrice');
        // if ($price == null) {
        //     return $next($request);
        // }
        // if ($maxPrice == null) {
        //     return $next($request);
        // }
        $builder = $next($request);
        if ($price && $maxPrice) {
            return $builder->whereBetween('price', [$price, $maxPrice]);
        } elseif ($price == null && $maxPrice != null) {
            return $builder->whereBetween('price', ['<=', $maxPrice]);
        } elseif ($maxPrice == null && $price != null) {
            return $builder->whereBetween('price', [$price, 100000000]);
        } else {
            return $builder;
        }
    }
}
