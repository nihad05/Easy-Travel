<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\BookProperty;
use App\Models\Comment;
use App\Models\Place;
use App\Models\PlaceFiles;
use App\Models\PropertySupply;
use App\Models\Supply;
use App\Models\Tour;
use App\Models\TourUser;
use App\Models\User;
use Illuminate\Support\Facades\Route;
use App\Models\Property;
use App\Models\PropertyFile;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DetailsController extends Controller
{
    public function place($id)//: View
    {
        $details = Place::query()->findOrFail($id);
        $images = PlaceFiles::query()->where('place_id', $id)->get();
        $recommendedPlaces = Place::query()
            ->whereHas('homeImage')
            ->with('homeImage')
            ->limit(4)
            ->get();

        $recGuide = User::query()
            ->with('guides')
            ->where('role', 'guide')
            ->inRandomOrder()
            ->limit(3)
            ->get();

        $title = $details->name;

        if ($details->safety >= 0 && $details->safety <= 20) {
            $safety = "Bad";
        } elseif ($details->safety >= 21 && $details->safety <= 40) {
            $safety = "Not bad";
        } elseif ($details->safety >= 41 && $details->safety <= 60) {
            $safety = "Normal";
        } elseif ($details->safety >= 61 && $details->safety <= 80) {
            $safety = "Good";
        } else {
            $safety = "Great";
        }
        if ($details->fun >= 0 && $details->fun <= 20) {
            $fun = "Bad";
        } elseif ($details->fun >= 21 && $details->fun <= 40) {
            $fun = "Not bad";
        } elseif ($details->fun >= 41 && $details->fun <= 60) {
            $fun = "Normal";
        } elseif ($details->fun >= 61 && $details->fun <= 80) {
            $fun = "Good";
        } else {
            $fun = "Great";
        }

        $comments = Comment::query()
            ->with('users')
            ->where('entity_id', $id)
            ->where('entity_type', 'place')
            ->get();

            return view(
                'client.details.place.index',
                compact([
                    'details',
                    'title',
                    'safety',
                    'images',
                    'comments',
                    'recGuide',
                    'recommendedPlaces'
                    , "fun"
                ])
            );
    }

    public function property($id)
    {
        $element = Property::query()
            ->with(['comments', 'supplies'])
            ->findOrFail($id);


        $title = $element->name;

        $supplies = Supply::all();

        $images = PropertyFile::query()
            ->where('property_id', $id)
            ->get();


        $recoProperty = Property::query()
            ->with('homeImage')
            ->limit(4)
            ->inRandomOrder()
            ->get();


        $recGuide = User::query()
            ->with('guides')
            ->where('role', 'guide')
            ->inRandomOrder()
            ->limit(3)
            ->get();

        if (auth()->user() && auth()->user() != null) {

            $bookedProperty = BookProperty::query()
                ->where('user_id', auth()->id())
                ->where('is_active', 0)
                ->where('hotel_id', $id)
                ->get();

            $image = User::query()->findOrFail(auth()->id())->image;

            return view('client.details.property.index', compact([
                'element',
                'title',
                'recoProperty',
                'image',
                'bookedProperty',
                'recGuide',
                'images',
                'supplies',
            ]));
        }

        $bookedProperty = null;

        return view('client.details.property.index', compact([
            'element',
            'title',
            'recoProperty',
            'recGuide',
            'images',
            'bookedProperty',
            'supplies',
        ]));

    }
    public function guide($id)
    {
        $guide = User::with("guides")->where('id', $id)->first();
        if (auth()->user() && auth()->user() != null) {
            $image = User::findOrFail(auth()->id())->image;
        }
        $language = $guide->guides->languages;
        $languages = json_decode($language, true);
        $availble = $guide->guides->aviable_for;
        $availble_for = json_decode($availble, true);
        $guides = User::with('guides')->where('role', 'guide')->skip(0)->take(3)->get();
        $places = Place::inRandomOrder()->limit(4)->get();
        $comments = Comment::with('users')->where('entity_id', $id)->where('entity_type', 'guide')->get();
        $title = $guide->name;
        if (auth()->user() && auth()->user() != null) {
            return view('client.details.guide.index', compact(['image', 'title', 'guide', 'languages', 'availble_for', 'comments', 'places', 'guides']));
        } else {
            return view('client.details.guide.index', compact(['guide', 'title', 'languages', 'availble_for', 'comments', 'places', 'guides']));
        }
    }

    public function tourDetails($id)
    {
        $randGuides = User::query()
            ->inRandomOrder()
            ->limit(5)
            ->where('role', 'guide')
            ->get();

        $tourUsers = TourUser::query()
                ->from('tour_users as tu')
                ->select('tu.id', 'u.image')
                ->join('users as u', 'u.id', 'tu.user_id')
                ->where('tu.tour_id', $id)
                ->get();

        $user = TourUser::query()->where(['tour_id' => $id, 'user_id' => auth()->id()])->first();

        $tour = Tour::withTrashed()
            ->with(['hotels.hotel.image', 'guides.guide', 'host', 'transports'])
            ->where('id', $id)
            ->first();

        return view('client.details.tour.index', compact(['tour', 'user', 'tourUsers', 'randGuides']));
    }
}
