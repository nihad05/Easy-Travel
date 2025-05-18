<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Place;
use App\Models\Property;
use App\Models\Tour;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

class HomeController extends Controller
{
    public function index()
    {

        if (auth()->user() && auth()->user() != null)
            $image = User::findOrFail(auth()->id())->image;

        $guide = User::with('guides')
            ->with('comments')
            ->with('selections')
            ->where('role', 'guide')
            ->skip(0)->take(6)
            ->get();

        $places = Place::query()
            ->with('comments')
            ->with('selections')
            ->from('places as p')
            ->select('p.*', 'pf.image as image')
            ->join('place_files as pf', 'pf.place_id', '=', 'p.id')
            ->where('pf.show_home', 1)
            ->skip(0)->take(4)
            ->get();

        $properties = Property::query()
            ->with(['comments', 'selections', 'homeImage'])
            ->skip(0)->take(4)
            ->get();

        $tours = Tour::withTrashed()
            ->select('t.*', 'tt.status')
            ->from('tours as t')
            ->join('tour_transactions as tt', 'tt.tour_id',  't.id')
            ->where('tt.status', 1)
            ->get();

        if (auth()->user() && auth()->user() != null) {
            return view('client.home.index', compact(['places', 'properties', 'image', 'guide', 'tours']));
        } else {
            return view('client.home.index', compact(['places', 'properties', 'guide', 'tours']));
        }

    }

}
