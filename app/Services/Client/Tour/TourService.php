<?php

namespace App\Services\Client\Tour;

use App\Http\Requests\Client\Tour\StoreRequest;
use App\Models\Place;
use App\Models\Property;
use App\Models\Tour;
use App\Models\TourPlace;
use App\Models\TourTransaction;
use App\Models\TourTransport;
use App\Models\Transport;
use App\Models\User;
use App\Traits\MediaTrait;
use App\Traits\TourTrait;

class TourService
{
    use MediaTrait, TourTrait;

    public function storeFirstStep(StoreRequest $request)
    {
        $request->validate([
            'tourName' => ['required'],
            'startLocation' => ['required', 'string'],
            'price' => ['required', 'integer'],
            'transports' => ['required'],
            'places' => ['required'],
            'startDate' => ['required'],
            'endDate' => ['required'],
            'people' => ['required'],
            'about' => ['required'],
            'image' => ['required'],
        ]);

        $newFile = $this->uploadImage($request->file('image'), 'tourImgs');

        $insert = Tour::query()->create([
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

        TourPlace::query()->insert($this->prepareTourPlaces($request->places, $insert->id));
        TourTransport::query()->insert($this->prepareTourTransport($request->transports, $insert->id));

        if ($insert) {
            return redirect()->route('tourPlan.create', ['step' => 2, 'id' => $insert->id]);
        }

        return back()->with('error', 'Something went wrong!');
    }

    public function storeSecondStep(int $tour_id) {}

    public function storeThirdStep($tour_id, StoreRequest $request)
    {
        $request->validate([
            'cardNumber' => 'required|integer',
            'expirationDate' => 'required|integer',
            'cvc' => 'required|integer',
            'price' => 'required|integer',
        ]);

        $transaction = TourTransaction::query()
            ->where('tour_id', $tour_id)
            ->where('user_id', auth()->id())
            ->where('status', 0)
            ->first();

        if (! $transaction) {
            abort(404);
        }
        $transaction->update([
            'status' => 1,
            'price' => $request->price,
        ]);

        return to_route('tourPlan.create', ['step' => 4]);

    }

    public function createFirstStep()
    {
        $tourPlaces = Place::query()->select('id', 'name')->get();
        $tourTransports = Transport::query()->select('id', 'name')->get();

        return view('client.tourPlans.tour1', compact(['tourPlaces', 'tourTransports']));
    }

    public function createSecondStep($id)
    {
        $tourPlan = $this->tourCheck($id);

        $hotels = Property::query()
            ->with(['tour_property' => function ($q) use ($id) {
                $q->where('host_id', auth()->id())->where('entity_type', 'place')->where('tour_id', $id);
            }, 'image'])
            ->orderBy('name')
            ->get();

        $addedHotels = $hotels->whereNotNull('tour_property');
        $hotels = $hotels->whereNull('tour_property');

        $guides = User::query()
            ->with(['tour_guide' => function ($query) use ($id) {
                $query->where('host_id', auth()->id())->where('entity_type', 'guide')->where('tour_id', $id);
            }])
            ->where('role', 'guide')
            ->orderBy('name')
            ->get();

        $addedGuides = $guides->whereNotNull('tour_guide');
        $guides = $guides->whereNull('tour_guide');

        return view('client.tourPlans.tour2', compact([
            'tourPlan',
            'hotels',
            'guides',
            'id',
            'addedHotels',
            'addedGuides',
        ]));
    }

    public function createThirdStep($tour_id, $price) {}

    public function createFourthStep()
    {
        return view('client.tourPlans.tour3');
    }
}
