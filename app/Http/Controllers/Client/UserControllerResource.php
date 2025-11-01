<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\BookProperty;
use App\Models\GuideBook;
use App\Models\Tour;
use App\Models\TourUser;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class UserControllerResource extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $today = Carbon::now();
        $todayFormatted = $today->toDateString();
        $day = explode('-', $todayFormatted);
        $aviable_for = null;
        $languages = null;

        $activeHotels = [];
        $pastHotels = [];
        $activeGuides = [];
        $pastGuides = [];

        $guideBookings = GuideBook::query()
            ->from('guide_books as gb')
            ->join('users as u', 'u.id', 'gb.user_id');

        $bookings = BookProperty::query()->where('user_id', auth()->id())->with('hotel')->get();

        foreach ($guideBookings as $item) {
            $guideEndTime = $item->end_date;
            $finish = explode('-', $guideBookings);
            if ($day[2] == $finish[2]) {
                $booking = GuideBook::findOrFail($item->id);
                $booking->update([
                    'is_active' => 1,
                ]);
            }
        }
        foreach ($bookings as $item) {
            $bookingEndTime = $item->end_time;
            $end = explode('-', $bookingEndTime);
            if ($day[2] == $end[2]) {
                $update = BookProperty::findOrFail($item->id);
                $update->update([
                    'is_active' => 1,
                ]);
            }
        }

        foreach ($guideBookings as $item) {
            if ($item->is_active == 0) {
                $activeGuides[] = $item;
            }
            if ($item->is_active == 1) {
                $pastGuides[] = $item;
            }
        }
        foreach ($bookings as $item) {
            if ($item->is_active == 0) {
                $activeHotels[] = $item;
            }
            if ($item->is_active == 1) {
                $pastHotels[] = $item;
            }
        }

        $activeTour = TourUser::query()
            ->from('tour_users as tu')
            ->select('t.name', 't.price', 't.start_time', 't.id as tour_id', 't.start_location', 't.image')
            ->join('tours as t', 't.id', 'tu.tour_id')
            ->where(['tu.user_id' => auth()->id(), 't.status' => 1])
            ->get();

        $pastTour = TourUser::query()
            ->from('tour_users as tu')
            ->select('t.name', 't.price', 't.start_time', 't.id as tour_id', 't.start_location', 't.image')
            ->join('tours as t', 't.id', 'tu.tour_id')
            ->where('tu.user_id', auth()->id())
            ->where('t.status', '!=', 1)
            ->get();

        if (auth()->user()->role == 'guide') {
            $user = User::with('guides')->with('blogs')->where('id', auth()->id())->where('role', 'guide')->first();
            $language = $user->guides->languages;
            $languages = json_decode($language, true);
            $aviable = $user->guides->aviable_for;
            $aviable_for = json_decode($aviable);
        } elseif (auth()->user()->role == 'user') {
            $user = User::findOrFail($id);
        } elseif (auth()->user()->role == 'host') {
            $user = User::findOrFail($id);

        }
        $requests = User::with('requests')->where('id', $id)->first();
        $request = $requests->requests;
        $image = $user->image;
        if ($user->role == 'host') {
            $tour = Tour::where('host_id', auth()->id())->get();
        }
        $title = $user->name;
        if ($aviable_for && $aviable_for != null && $languages && $languages != null) {
            return view('client.userPage.index', compact(['user', 'title', 'request', 'image', 'pastGuides', 'activeGuides', 'activeHotels', 'pastHotels', 'activeTour', 'pastTour', 'aviable_for', 'languages']));
        } elseif ($user->role == 'host') {
            return view('client.userPage.index', compact(['user', 'title', 'request', 'activeGuides', 'tour', 'pastGuides', 'activeHotels', 'pastHotels', 'activeTour', 'pastTour']));
        } else {
            return view('client.userPage.index', compact(['user', 'title', 'request', 'activeGuides', 'activeHotels', 'pastGuides', 'pastHotels', 'activeTour', 'pastTour']));
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
