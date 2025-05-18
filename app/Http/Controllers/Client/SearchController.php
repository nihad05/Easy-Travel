<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\GuideInfo;
use App\Models\Place;
use App\Models\Property;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Pipeline\Pipeline;

class SearchController extends Controller
{
    public function searchMain(Request $request)
    {
        $places = new Place;
        $places = app(Pipeline::class)
            ->send($places)
            ->through([
                \App\queryFilter\Name::class,
                \App\queryFilter\Location::class,
                \App\queryFilter\MinPrice::class,
                \App\queryFilter\MaxPrice::class,
            ])
            ->thenReturn();
        $place = $places->paginate(8);

        $title = 'Search';
        return view('client.search.index', compact('place', 'title'));
    }
    public function searchProperty()
    {
        $properties = new Property;
        $properties = app(Pipeline::class)
            ->send($properties)
            ->through([
                \App\queryFilterProperty\Name::class,
                \App\queryFilterProperty\Location::class,
                \App\queryFilterProperty\MinPrice::class,
            ])
            ->thenReturn();

        $property = $properties->paginate(8);
        $title = 'Search';

        return view('client.search.property', compact('property', 'title'));

    }
    public function searchGuide()
    {
        $guides = User::where('role', 'guide')->with('guides');
        if (request()->has('name') && request()->get('name') != null) {
            $name = request()->get('name');
            $guides->where('name', 'like', "%" . $name . "%");
        }
        if (request()->has('location') && request()->get('location') != null) {
            $location = request()->get('location');
            $guides->where('location', 'like', "%" . $location . "%");
        }
        $minPrice = request()->get('minPrice');
        $maxPrice = request()->get('maxPrice');
        if ($minPrice !== null && $maxPrice !== null) {
            $guides->whereHas('guides', function ($query) use ($minPrice, $maxPrice) {
                $query->whereBetween('price', [$minPrice, $maxPrice]);
            });
        } elseif ($minPrice == null && $maxPrice != null) {
            $minPrice = 0;
            $guides->whereHas('guides', function ($query) use ($minPrice, $maxPrice) {
                $query->whereBetween('price', [$minPrice, $maxPrice]);
            });
        } elseif ($minPrice != null && $maxPrice == null) {
            $maxPrice = 10000000000;
            $guides->whereHas('guides', function ($query) use ($minPrice, $maxPrice) {
                $query->whereBetween('price', [$minPrice, $maxPrice]);
            });
        }
        $title = 'Search';

        $guide = $guides->paginate(9);
        return view('client.search.guide', compact('guide', 'title'));
    }
}
