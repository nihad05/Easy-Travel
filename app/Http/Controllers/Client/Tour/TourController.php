<?php

namespace App\Http\Controllers\Client\Tour;

use App\Enums\TourStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Client\Tour\StoreRequest;
use App\Models\Place;
use App\Models\Tour;
use App\Models\TourPlace;
use App\Models\TourTransport;
use App\Models\Transport;
use App\Models\User;
use App\Traits\MediaTrait;
use App\Traits\TourTrait;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;

class TourController extends Controller
{
    use MediaTrait, TourTrait;

    public function index(): Factory|View|Application
    {
        $tours = Tour::withTrashed()->where('host_id', auth()->id())->get();

        return view('client.tourPlans.index', compact('tours'));
    }

    public function create(): Factory|View|Application
    {
        $tourPlaces = Place::query()->select('id', 'name')->get();
        $tourTransports = Transport::query()->select('id', 'name')->get();

        return view('client.tourPlans.create', compact(['tourPlaces', 'tourTransports']));
    }

    public function store(StoreRequest $request): RedirectResponse
    {
        $newFile = $this->uploadImage($request->file('image'), 'tourImgs');

        $tour = Tour::query()->create([
            'name' => $request->tourName,
            'start_location' => $request->startLocation,
            'price' => $request->price,
            'start_time' => $request->startDate,
            'end_time' => $request->endDate,
            'people' => $request->people,
            'about' => $request->about,
            'host_id' => auth()->id(),
            'image' => $newFile,
        ]);

        TourPlace::query()->insert($this->prepareTourPlaces($request->places, $tour->id));
        TourTransport::query()->insert($this->prepareTourTransport($request->transports, $tour->id));

        if ($tour) {
            return redirect()->route('tourPlan.edit', ['tour' => $tour->id])->with(['step' => 2]);
        }

        return back()->with('error', 'Something went wrong!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $tour = Tour::query()
            ->with(['hotels.hotel', 'guides.guide', 'transports', 'places'])
            ->findOrFail($id);

        return view('client.tourPlans.show', compact('tour'));
    }

    public function edit($id): Factory|View|Application
    {
        $tourPlan = Tour::withTrashed()->where('host_id', auth()->id())->findOrFail($id);

        $addedHotels = DB::table('properties as p')
            ->select('p.id', 'p.name', 'p.location', 'p.price', 'ti.tour_id', 'pf.image',
                DB::raw('CONCAT(p.price * t.people, " ", "₼") as total_price'), 'ti.id as item_id'
            )
            ->join('tour_items as ti', 'ti.entity_id', 'p.id')
            ->join('tours as t', 't.id', 'ti.tour_id')
            ->join('property_files as pf', function ($property_files) {
                $property_files->on('pf.property_id', 'p.id')->select('pf.image', 'pf.property_id')->where('pf.show_home', 1);
            })
            ->where((['tour_id' => $id, 'entity_type' => 'place']))
            ->get();

        $addedGuides = User::query()
            ->from('users as u')
            ->select(
                'u.name',
                'u.image',
                'gi.price',
                't.start_time',
                't.end_time',
                'u.location',
                'ti.id as item_id'
            )
            ->join('guide_infos as gi', 'u.id', 'gi.user_id')
            ->join('tour_items as ti', function ($ti) use ($id) {
                $ti->on('ti.entity_id', 'u.id')->where(['entity_type' => 'guide', 'ti.tour_id' => $id]);
            })
            ->join('tours as t', 't.id', 'ti.tour_id')
            ->get();

        return view('client.tourPlans.edit', compact([
            'tourPlan',
            'id',
            'addedHotels',
            'addedGuides',
        ]));
    }

    public function update($id): RedirectResponse
    {
        $tour = Tour::withTrashed()->findOrFail($id);
        $update = $tour->update(['status' => TourStatus::WAITING->value]);

        if ($update) {
            return back()->with('We accepted your request and turn back soon!');
        }

        return back()->with('error', 'Something went wrong!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $tour = Tour::query()->withCount('users')->findOrFail($id);

        if ($tour->users_count === 0) {
            DB::table('tours')->where('id', $id)->delete();
        }
        $tour->delete();

        return back()->with('success', 'Tour has been deleted!');

    }
}
