<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Place\StoreRequest;
use App\Http\Requests\Admin\Place\UpdateRequest;
use App\Models\Place;
use App\Models\PlaceFiles;
use App\Traits\MediaTrait;
use Exception;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;

class PlaceController extends Controller
{
    use MediaTrait;

    /**
     * Display a listing of the resource.
     *
     * @return Response|Factory|View
     */
    public function index()// : Response|Factory|View
    {
        $place = Place::query()
            ->from('places as p')
            ->select('p.id as pId', 'p.name', 'p.price', 'p.location', 'pf.image as image', 'pf.show_home')
            ->join('place_files as pf', 'pf.place_id', '=', 'p.id')
            ->where('pf.show_home', 1)
            ->paginate(5);

        return view('admin.place.index', compact(['place']));
    }

    /**
     * @return void
     */
    public function show()
    {
        //
    }

    public function store(StoreRequest $request): RedirectResponse
    {
        try {
            $insert = $request->validated();

            $place = Place::query()->create($insert);

            PlaceFiles::query()
                ->create([
                    'image' => $this->uploadImage($request->file('image'), 'imgs'),
                    'show_home' => 1,
                    'place_id' => $place->id,
                ]);

            return back()->with('success', 'Created Successfully');
        } catch (Exception) {
            return back()->with('fail', 'Something went wrong');
        }
    }

    /**
     * Summary of edit
     *
     * @param  mixed  $place
     * @return Factory|View
     */
    public function edit($place)// : Factory|View
    {
        $place = Place::query()->findOrFail($place);
        $image = PlaceFiles::query()
            ->where('place_id', $place->id)
            ->where('show_home', 1)
            ->first()->image;

        if (! $place) {
            to_route('admin.notfound');
        }

        return view('admin.place.editPlace', compact(['place', 'image']));
    }

    /**
     * Summary of update
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $place
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UpdateRequest $request, $place): RedirectResponse
    {
        $editedPlace = Place::query()->findOrFail($place);
        $newFile = PlaceFiles::query()
            ->where('place_id', $editedPlace->id)
            ->where('show_home', 1)
            ->first()->image;

        if ($request->hasFile('image')) {
            $newFile = $this->uploadImage($request->file('image'), 'imgs');
        }

        $update = $editedPlace->update($request->validated());

        PlaceFiles::query()
            ->where('place_id', $editedPlace->id)
            ->where('show_home', 1)
            ->update([
                'image' => $newFile,
            ]);

        if ($update) {
            return back()->with('success', 'Place edited successfully');
        }

        return back()->with('error', 'Something went wrong');
    }

    /**
     * Summary of destroy
     *
     * @param  mixed  $place
     */
    public function destroy($place): RedirectResponse
    {
        $delete = Place::query()->findOrFail($place)->delete();

        if ($delete) {
            return back()->with('success', 'Place deleted successfully');
        }

        return back()->with('error', 'Something went wrong');
    }
}
