<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Place;
use App\Models\PlaceFiles;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class AdminPlaceController extends Controller
{
    // public function index()
    // {
    //     $places = new Place;
    //     $place = $places->paginate(5);
    //     $select2 = 'selected';
    //     return view('admin.place.index', compact(['place', 'select2']));
    // }
    // public function store(Request $request){
    //     $request->validate([
    //         'name' => ['required'],
    //         'about' => ['required'],
    //         'price' => ['required'],
    //         'location' => ['required'],
    //         'safety' => ['required'],
    //         'fun' => ['required'],
    //         'internet' => ['required'],
    //         'image' => ['required'],
    //     ]);
    //     if ($request->hasFile('image')) {
    //         $file = $request->file('image');
    //         $extension = $file->getClientOriginalExtension();
    //         $newFile = time() . "." . $extension;
    //     }
    //     $safety = 1;
    //     if ($request->safety == 'bad') {
    //         $safety *= rand(1, 20);
    //     } elseif ($request->safety == 'notbad') {
    //         $safety *= rand(21, 40);
    //     } elseif ($request->safety == 'normal') {
    //         $safety *= rand(41, 60);
    //     } elseif ($request->safety == "good") {
    //         $safety *= rand(61, 80);
    //     } else {
    //         $safety *= rand(81, 100);
    //     }

    //     $fun = 1;
    //     if ($request->fun == 'bad') {
    //         $fun *= rand(1, 20);
    //     } elseif ($request->fun == 'notbad') {
    //         $fun *= rand(21, 40);
    //     } elseif ($request->fun == 'normal') {
    //         $fun *= rand(41, 60);
    //     } elseif ($request->fun == "good") {
    //         $fun *= rand(61, 80);
    //     } else {
    //         $fun *= rand(81, 100);
    //     }
    //     $insert = Place::create([
    //         'name' => $request->name,
    //         'about' => $request->about,
    //         'price' => $request->price,
    //         'location' => $request->location,
    //         'safety' => $safety,
    //         'fun' => $fun,
    //         'internet' => $request->internet,
    //         'image' => $newFile,
    //     ]);
    //     $file->move(public_path('images/imgs'), $newFile);
    //     if ($insert) {
    //         return back();
    //     }
    // }
    // public function destroy($place)
    // {
    //     $delete = Place::findOrFail($place)->delete();
    //     return back();
    // }
    public function edit($place): View
    {

        $place = Place::query()->findOrFail($place) ?? 1;

        if ($place->safety >= 0 && $place->safety <= 20) {
            $safety = 'Bad';
        } elseif ($place->safety >= 21 && $place->safety <= 40) {
            $safety = 'Not bad';
        } elseif ($place->safety >= 41 && $place->safety <= 60) {
            $safety = 'Normal';
        } elseif ($place->safety >= 61 && $place->safety <= 80) {
            $safety = 'Good';
        } else {
            $safety = 'Great';
        }
        if ($place->fun >= 0 && $place->fun <= 20) {
            $fun = 'Bad';
        } elseif ($place->fun >= 21 && $place->fun <= 40) {
            $fun = 'Not bad';
        } elseif ($place->fun >= 41 && $place->fun <= 60) {
            $fun = 'Normal';
        } elseif ($place->fun >= 61 && $place->fun <= 80) {
            $fun = 'Good';
        } else {
            $fun = 'Great';
        }

        return view('admin.place.editPlace', compact(['place', 'fun', 'safety']));
    }

    public function update(Request $request, $place): View
    {
        $editedPlace = Place::query()->findOrFail($place);
        $img = $editedPlace->image;
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $extension = $file->getClientOriginalExtension();
            $newFile = time().'.'.$extension;
            $file->move(public_path('images/imgs'), $newFile);
        } else {
            $newFile = $img;
        }
        $safety = 1;
        if ($request->safety == 'bad') {
            $safety *= rand(1, 20);
        } elseif ($request->safety == 'notbad') {
            $safety *= rand(21, 40);
        } elseif ($request->safety == 'normal') {
            $safety *= rand(41, 60);
        } elseif ($request->safety == 'good') {
            $safety *= rand(61, 80);
        } else {
            $safety *= rand(81, 100);
        }

        $fun = 1;
        if ($request->fun == 'bad') {
            $fun *= rand(1, 20);
        } elseif ($request->fun == 'notbad') {
            $fun *= rand(21, 40);
        } elseif ($request->fun == 'normal') {
            $fun *= rand(41, 60);
        } elseif ($request->fun == 'good') {
            $fun *= rand(61, 80);
        } else {
            $fun *= rand(81, 100);
        }
        $editArr = [
            'name' => $request->name,
            'about' => $request->about,
            'price' => $request->price,
            'location' => $request->location,
            'safety' => $safety,
            'fun' => $fun,
            'internet' => $request->internet,
            'image' => $newFile,
        ];
        $editedPlace->update($editArr);

        return back()->with('success', 'Place edited successfully');
    }

    public function index($id): View
    {
        $files = PlaceFiles::query()->where('place_id', $id)->get();
        $name = Place::query()->where('id', $id)->first()->name;

        return view('admin.place.images', compact(['files', 'name', 'id']));
    }

    public function store(Request $request, $id): RedirectResponse
    {
        $request->validate([
            'file' => ['required', 'array'],
            'file.*' => ['required'],
        ]);

        foreach ($request->file('file') as $file) {
            $extension = $file->getClientOriginalExtension();
            $newFileName = Str::uuid().'.'.$extension;
            $file->move(public_path('/images/imgs'), $newFileName);
            $image = PlaceFiles::create([
                'image' => $newFileName,
                'place_id' => $id,
            ]);
        }

        return redirect()->back()->with('success', 'Images uploaded successfully.');
    }

    public function destroy($id, $image): RedirectResponse
    {
        $delete = PlaceFiles::query()
            ->where('place_id', $id)
            ->where('id', $image)
            ->delete();

        if ($delete) {
            return back()->with('success', 'Image deleted successfully!');
        }
    }
}
