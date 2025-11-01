<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\HostRequest;
use App\Models\Place;
use App\Models\Tour;
use App\Models\TourItem;
use App\Models\TourUser;
use App\Models\User;
use Illuminate\Http\Request;

class TourController extends Controller
{
    public function places()
    {
        $tourId = request()->id;
        $count = 0;
        $guideCount = 0;
        $arr = [];
        $guideArr = [];

        $places = Place::all();

        $guides = User::query()
            ->where('role', 'guide')
            ->get();

        $addedGuides = TourItem::where('entity_type', 'guide')
            ->where('host_id', auth()->id())
            ->where('tour_id', $tourId)
            ->with('guides')
            ->get();

        $tour = Tour::findOrFail(request()->id);

        $addedPlaces = TourItem::query()
            ->where('host_id', auth()->id())
            ->where('entity_type', 'place')
            ->where('tour_id', $tourId)
            ->with('places')
            ->get();

        $request = HostRequest::query()
            ->where('user_id', auth()->id())
            ->where('tours_id', $tourId)
            ->first();

        if ($addedPlaces != null) {
            $count = count($addedPlaces);
        }

        if ($addedGuides != null) {
            $guideCount = count($addedGuides);
        }

        $arr = $places->filter(function ($place) use ($addedPlaces) {
            return ! $addedPlaces->contains('entity_id', $place->id);
        });
        $guideArr = $guides->filter(function ($guide) use ($addedGuides) {
            return ! $addedGuides->contains('entity_id', $guide->id);
        });

        $placePrice = 0;
        $guidePrice = 0;
        // if ($addedPlaces != null) {
        foreach ($addedPlaces as $item) {
            $placePrice += $item->places->price * $tour->people;
        }
        foreach ($addedGuides as $item) {
            $guidePrice += $item->guides->guides->price;
        }
        $value = $guidePrice + $placePrice;
        $title = 'Tour Plan 2';

        return view('client.tourPlans.tour2', compact(['places', 'addedPlaces', 'tour', 'title', 'arr', 'value', 'addedGuides', 'count', 'guides', 'request', 'guideArr', 'guideCount', 'placePrice', 'request']));
    }

    public function data(Request $request)
    {
        $places2 = Place::all();
        $request->validate([
            'tourName' => ['required'],
            'startLocation' => ['required', 'string'],
            'price' => ['required', 'integer'],
            'transport' => ['required'],
            'places' => ['required'],
            'startDate' => ['required'],
            'endDate' => ['required'],
            'people' => ['required'],
            'about' => ['required'],
            'image' => ['required'],
        ]);

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $extension = $file->getClientOriginalExtension();
            $newFile = time().'.'.$extension;
            $file->move(public_path('/images/tourImgs'), $newFile);
        }
        $location = $request->startLocation;
        $transport = json_encode($request->transport); // transports tour_plan_transports
        $places = json_encode($request->places); // places tour_plan_places
        $insert = Tour::create([ // tours
            'name' => $request->tourName,
            'start_location' => $location,
            'price' => $request->price,
            'transport' => $transport,
            'travel_places' => $places,
            'start_time' => $request->startDate,
            'end_time' => $request->endDate,
            'people' => $request->people,
            'about' => $request->about,
            'host_id' => auth()->id(),
            'image' => $newFile,
        ]);
        // // $item = Tour::where('transport', $transport)->where('travel_places', $places)->where('start_location', $location)->where('end_time', $request->endDate)->where('start_time', $request->startDate)->where('people', $request->people)->where('about', $request->about)->first();
        // // $id = $item->id;
        $id = Tour::findOrFail($insert->id);
        if ($insert) {
            return back()->with('id', $id)->with('success', 'You have compleated first step for second step click link.');
        }
    }

    //    public function index2()
    //    {
    //        $arr = [
    //            ['name' => 'Taj Mahal'],
    //            ['name' => 'Great Wall of China'],
    //            ['name' => 'Machu Picchu'],
    //            ['name' => 'Eiffel Tower'],
    //            ['name' => 'Pyramids of Giza'],
    //            ['name' => 'Statue of Liberty'],
    //            ['name' => 'Colosseum'],
    //            ['name' => 'Santorini, Greece'],
    //            ['name' => 'Grand Canyon'],
    //            ['name' => 'Venice, Italy'],
    //            ['name' => 'Niagara Falls'],
    //            ['name' => 'Stonehenge'],
    //            ['name' => 'The Louvre'],
    //            ['name' => 'Petra, Jordan'],
    //            ['name' => 'Angkor Wat'],
    //            ['name' => 'Sistine Chapel'],
    //            ['name' => 'The Vatican'],
    //            ['name' => 'Mona Lisa'],
    //            ['name' => 'Museum of Modern Art, New York'],
    //            ['name' => 'Alhambra, Spain'],
    //            ['name' => 'Mount Everest'],
    //            ['name' => 'Marrakech, Morocco'],
    //            ['name' => 'Red Square, Moscow'],
    //            ['name' => 'The Great Barrier Reef'],
    //            ['name' => 'Ayers Rock (Uluru), Australia'],
    //            ['name' => 'Amazon Rainforest'],
    //            ['name' => 'Bora Bora, French Polynesia'],
    //            ['name' => 'Antelope Canyon, Arizona'],
    //            ['name' => 'The Acropolis, Athens'],
    //            ['name' => 'Banff National Park, Canada'],
    //            ['name' => 'Iguazu Falls, Argentina/Brazil'],
    //            ['name' => 'Chichen Itza, Mexico'],
    //            ['name' => 'The Palace Museum (Forbidden City), Beijing'],
    //            ['name' => 'Dubai, United Arab Emirates'],
    //            ['name' => 'Galapagos Islands, Ecuador'],
    //            ['name' => 'The Golden Gate Bridge, San Francisco'],
    //            ['name' => 'Victoria Falls, Zambia/Zimbabwe'],
    //            ['name' => 'Kilimanjaro, Tanzania'],
    //            ['name' => 'Santorini, Greece'],
    //            ['name' => 'The Amazon River'],
    //            ['name' => 'Mesa Verde National Park, Colorado'],
    //            ['name' => 'Mount Fuji, Japan'],
    //            ['name' => 'Yellowstone National Park, Wyoming'],
    //            ['name' => 'The Louvre, Paris'],
    //            ['name' => 'The Alhambra, Spain'],
    //            ['name' => 'Angkor Wat, Cambodia'],
    //            ['name' => 'Easter Island, Chile'],
    //            ['name' => 'The Grand Tetons, Wyoming'],
    //            ['name' => 'Meteora, Greece'],
    //            ['name' => 'The Serengeti, Tanzania'],
    //            ['name' => 'Bali, Indonesia'],
    //            ['name' => 'The Dead Sea, Israel/Jordan'],
    //            ['name' => 'The Galapagos Islands, Ecuador'],
    //            ['name' => 'The Great Wall of China'],
    //            ['name' => 'Halong Bay, Vietnam'],
    //            ['name' => 'Antelope Canyon, Arizona'],
    //            ['name' => 'The Colosseum, Rome'],
    //            ['name' => 'Machu Picchu, Peru'],
    //            ['name' => 'The Parthenon, Athens'],
    //            ['name' => 'The Pyramids of Giza, Egypt'],
    //            ['name' => 'The Taj Mahal, India'],
    //            ['name' => 'Chichen Itza, Mexico'],
    //            ['name' => 'The Great Barrier Reef, Australia'],
    //            ['name' => 'The Inca Trail, Peru'],
    //            ['name' => 'The Moai Statues of Easter Island'],
    //            ['name' => 'The Palace of Versailles, France'],
    //            ['name' => 'The Petra, Jordan'],
    //            ['name' => 'The Red Square, Moscow'],
    //            ['name' => 'The Serengeti, Tanzania'],
    //            ['name' => 'The Stonehenge, England'],
    //            ['name' => 'The Sydney Opera House, Australia'],
    //            ['name' => 'The Venice, Italy'],
    //            ['name' => 'The Victoria Falls, Zambia/Zimbabwe'],
    //            ['name' => 'The Yellowstone National Park, Wyoming'],
    //            ['name' => 'The Mount Everest, Nepal'],
    //            ['name' => 'The Marrakech, Morocco'],
    //            ['name' => 'The Amazon Rainforest'],
    //            ['name' => 'The Bora Bora, French Polynesia'],
    //            ['name' => 'The Dubai, United Arab Emirates'],
    //            ['name' => 'The Galapagos Islands, Ecuador'],
    //            ['name' => 'The Golden Gate Bridge, San Francisco'],
    //            ['name' => 'The Kilimanjaro, Tanzania'],
    //            ['name' => 'The Santorini, Greece'],
    //            ['name' => 'The Amazon River'],
    //            ['name' => 'The Mesa Verde National Park, Colorado'],
    //            ['name' => 'The Mount Fuji, Japan'],
    //            ['name' => 'The Yellowstone National Park, Wyoming'],
    //            ['name' => 'The Louvre, Paris'],
    //            ['name' => 'The Alhambra, Spain'],
    //            ['name' => 'The Angkor Wat, Cambodia'],
    //            ['name' => 'The Easter Island, Chile'],
    //            ['name' => 'The Grand Tetons, Wyoming'],
    //            ['name' => 'The Meteora, Greece'],
    //            ['name' => 'The Serengeti, Tanzania'],
    //            ['name' => 'The Bali, Indonesia'],
    //            ['name' => 'The Dead Sea, Israel/Jordan'],
    //        ];
    //        $arr2 = [
    //            [
    //                'name' => 'Car',
    //            ],
    //            [
    //                'name' => 'Caravan',
    //            ],
    //            [
    //                'name' => 'Minivan',
    //            ],
    //            [
    //                'name' => 'Plane',
    //            ],
    //            [
    //                'name' => 'Ship',
    //            ],
    //            [
    //                'name' => 'Bus',
    //            ]
    //        ];
    //        $title = 'Tour plan 1';
    //        return view('client.tourPlans.tour1', compact(['arr', "arr2", 'title']));
    //    }

    public function addPlace(Request $request, $id)
    {
        $insert = TourItem::create([
            'entity_type' => 'place',
            'entity_id' => $request->id,
            'tour_id' => $id,
            'host_id' => auth()->id(),
        ]);

        if ($insert) {
            return back()->with('success', 'Place added this tour successfully');
        }
    }

    public function guideAdd(Request $request, $id)
    {
        $insert = TourItem::create([
            'entity_type' => 'guide',
            'entity_id' => $request->userId,
            'tour_id' => $id,
            'host_id' => auth()->id(),
        ]);
        if ($insert) {
            return back()->with('success', 'Guide added this tour successfully!');
        }
    }

    public function tourPlaceDelete($id)
    {
        $tourItem = TourItem::query()->findOrFail($id);
        $tourItem->delete();

        return back()->with('success', 'Item removed!');
    }

    public function tourReqeust($id)
    {
        $insert = HostRequest::create([
            'type' => 'tour',
            'user_id' => auth()->id(),
            'tours_id' => $id,
        ]);
        if ($insert) {
            return back()->with('We accepted your request and turn back soon!');
        }
    }

    public function tourApprove($id)
    {
        $tour = Tour::findOrFail($id);
        $success = 'Tour price has paid successfully';
        $tour->update([
            'is_active' => 1,
        ]);
        $title = 'Tour Plan 3';

        return view('client.tourPlans.tour3', compact('success', 'title'));
    }

    public function tourJoin($id)
    {
        $insert = TourUser::query()->create([
            'user_id' => auth()->id(),
            'tour_id' => $id,
        ]);

        if ($insert) {
            return back()->with('success', "You paid tour's price, We make contact with you as soon as!!");
        }
    }
}
