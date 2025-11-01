<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Place;
use App\Models\Property;
use App\Models\Selection;
use App\Models\User;

class SelectionController extends Controller
{
    public function place($id)
    {
        $insert = Selection::create([
            'entity_type' => 'place',
            'entity_id' => $id,
            'user_id' => auth()->user()->id,
        ]);
        if ($insert) {
            return back()->with('success', 'Place added your selections');
        }
    }

    public function deletePlace($id)
    {
        $place = Selection::where('entity_type', 'place')->where('entity_id', $id)->where('user_id', auth()->id())->first();

        if ($place != null) {
            $place->delete();

            return back();
        }
    }

    public function property($id)
    {
        $insert = Selection::create([
            'entity_type' => 'property',
            'entity_id' => $id,
            'user_id' => auth()->user()->id,
        ]);
        if ($insert) {
            return back()->with('success', 'Property added your selections');
        }
    }

    public function deleteProperty($id)
    {
        $property = Selection::where('entity_type', 'property')->where('entity_id', $id)->where('user_id', auth()->id());
        if ($property != null) {
            $property->delete();
        }

        return back();
    }

    public function guide($id)
    {
        $insert = Selection::create([
            'entity_type' => 'guide',
            'entity_id' => $id,
            'user_id' => auth()->id(),
        ]);
        if ($insert) {
            return back()->with('success', 'Guide added successfully to selections!');
        }
    }

    public function guideDelete($id)
    {
        $item = Selection::where('entity_type', 'guide')->where('entity_id', $id)->where('user_id', auth()->id());
        if ($item != null) {
            $item->delete();
        }

        return back();
    }

    public function placeFav()
    {
        $places = User::with('items')->where('id', auth()->id())->first();
        $items = $places->items;
        $ids = [];
        foreach ($items as $item) {
            $ids[] = $item->entity_id;
        }
        $products = [];
        foreach ($ids as $item) {
            $products[] = Place::where('id', $item)->first();
        }
        $title = 'Favorites';

        return view('client.favorites.place', compact('products', 'title'));
    }

    public function propertyFav()
    {
        $property = User::with('favProperty')->where('id', auth()->id())->first();
        $title = 'Favorites';
        $items = $property->favProperty;
        $ids = [];
        foreach ($items as $id) {
            $ids[] = $id->entity_id;
        }
        $products = [];
        foreach ($ids as $item) {
            $products[] = Property::where('id', $item)->first();
        }

        return view('client.favorites.property', compact('products', 'title'));
    }

    public function guideFav()
    {
        $guide = User::with('favGuide')->where('id', auth()->id())->first();
        $guides = $guide->favGuide;
        $ids = [];
        $title = 'Favorites';
        foreach ($guides as $item) {
            $ids[] = $item->entity_id;
        }
        $products = [];
        foreach ($ids as $item) {
            $products[] = User::with('guides')->where('id', $item)->first();
        }

        return view('client.favorites.guide', compact('products', 'title'));
    }
}
