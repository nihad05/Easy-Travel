<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Http\Requests\Client\Tour\Pay\StoreRequest;
use App\Models\BookProperty;
use App\Models\GuideBook;
use App\Models\Property;
use App\Models\Tour;
use App\Models\TourTransaction;
use App\Models\User;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index($id)
    {
        $property = Property::with('comments')->findOrFail($id);
        $comments = $property->comments;
        $count = count($comments);
        $rooms = $property->bed_count / 2;
        $room = floor($rooms);
        $title = 'Booking';

        return view('client.payments.property', compact(['property', 'title', 'count', 'room']));
    }

    public function propertyBook(Request $request, $id)
    {
        $request->validate([
            'startDate' => ['required'],
            'endDate' => ['required'],
            'rooms' => ['required', 'integer'],
            'cardType' => ['required'],
            'cardNumber' => ['required', 'integer'],
            'expiration' => ['required', 'integer'],
            'cvv' => ['required', 'integer'],
        ]);

        $insert = BookProperty::create([
            'start_time' => $request->startDate,
            'end_time' => $request->endDate,
            'room_count' => $request->rooms,
            'hotel_id' => $id,
            'user_id' => auth()->id(),
        ]);

        if ($insert) {
            return back()->with('success', 'You booked hotel successfully');
        }
    }

    public function guide($id)
    {
        $guide = User::findOrFail($id);
        $title = 'Booking';

        return view('client.payments.guide', compact(['guide', 'title']));
    }

    public function guidePay($id, Request $request)
    {
        $request->validate([
            'startDate' => ['required'],
            'endDate' => ['required'],
            'totalPrice' => ['required'],
            'cardType' => ['required'],
            'cardNumber' => ['required', 'integer'],
            'expiration' => ['required', 'integer'],
            'cvv' => ['required', 'integer'],
            'street' => ['required'],
            'city' => ['required'],
            'zipCode' => ['required'],
            'suit' => ['required'],
            'state' => ['required'],
            'country' => ['required'],
        ]);

        $guide = new GuideBook;
        $guide->create([
            'start_date' => $request->startDate,
            'end_date' => $request->endDate,
            'total_price' => $request->totalPrice,
            'street' => $request->street,
            'city' => $request->city,
            'zip_code' => $request->zipCode,
            'state' => $request->state,
            'suit' => $request->suit,
            'country' => $request->country,
            'user_id' => auth()->id(),
            'guide_id' => $id,
        ]);
        if ($guide) {
            return back()->with('success', 'You booked guide successfully');
        }
    }

    public function payAndPublish($tour_id, Request $request)
    {
        $price = $request->price;
        $tour = Tour::withTrashed()->findOrFail($tour_id);

        TourTransaction::query()
            ->create([
                'tour_id' => $tour_id,
                'user_id' => $tour->host_id,
                'price' => $price,
                'status' => 0,
            ]);

        return to_route('tourPlan.payment.page', $tour->id)->with('price', $price);

    }

    public function paymentPage($tour_id)
    {
        $tour = Tour::withTrashed()
            ->from('tours as t')
            ->select('t.*', 'tr.price')
            ->join('tour_transactions as tr', 'tr.tour_id', 't.id')
            ->where('tour_id', $tour_id)
            ->first();

        return view('client.payment.index', compact(['tour']))->with('price', $tour->price);
    }

    public function paymentStore($id, StoreRequest $request)
    {
        $transaction = TourTransaction::query()
            ->where('tour_id', $id)
            ->where('user_id', auth()->id())
            ->where('status', 0)
            ->first();

        $transaction->update([
            'status' => 1,
            'price' => $request->price,
        ]);

        return to_route('tourPlan.approved');

    }
}
