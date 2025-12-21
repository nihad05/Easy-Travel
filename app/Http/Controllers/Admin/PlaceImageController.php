<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Place\Images\IndexRequest;
use App\Http\Requests\Admin\Place\Images\StoreRequest;
use App\Models\Place;
use App\Models\PlaceFiles;
use App\Traits\MediaTrait;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class PlaceImageController extends Controller
{
    use MediaTrait;

    public function index(IndexRequest $request): Factory|View
    {
        $place_id = $request->get('place_id');
        $files = PlaceFiles::query()->where('place_id', $place_id)->get();
        $name = Place::query()->where('id', $place_id)->first()->name;

        return view('admin.place.images', compact(['files', 'name', 'place_id']));
    }

    /**
     * @param  $id
     */
    public function store(StoreRequest $request): RedirectResponse
    {
        $images = [];

        foreach ($request->file('file') as $file) {
            $newFileName = $this->uploadImage($file, 'imgs');

            $images[] = [
                'image' => $newFileName,
                'place_id' => $request->get('id'),
            ];
        }

        $insert = PlaceFiles::query()->insert($images);

        if ($insert) {
            return back()->with('success', 'Images uploaded successfully.');
        }

        return back()->with('fail', 'Something went wrong.');
    }

    public function destroy($id): RedirectResponse
    {
        $place_file = PlaceFiles::query()->with('place')->findOrFail($id);

        if ($place_file->delete()) {
            return back()->with('success', 'Image deleted successfully!');
        }

        return back()->with('fail', 'Something went wrong.');
    }
}
