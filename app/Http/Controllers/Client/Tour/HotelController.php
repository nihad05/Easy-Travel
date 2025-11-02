<?php

namespace App\Http\Controllers\Client\Tour;

use App\Http\Controllers\Controller;
use App\Models\Property;
use App\Models\TourItem;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HotelController extends Controller
{
    /**
     * @param  $id
     * @return JsonResponse
     */
    public function index($tour_id)
    {
        $hotels = Property::query()
            ->from('properties as p')
            ->select('p.id', 'p.name')
            ->leftJoin(DB::raw("(select entity_type, entity_id, tour_id from tour_items where entity_type = 'place' and tour_id = $tour_id) as ti"),
                'ti.entity_id', '=', 'p.id')
            ->leftJoin('tours as t', 't.id', '=', 'ti.tour_id')
            ->whereNull('t.id')
            ->orderBy('p.name')
            ->get();

        return response()->json([
            'data' => $hotels,
        ], 200);
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
    public function store(Request $request, $id)
    {
        $insert = TourItem::query()->create([
            'entity_type' => 'place',
            'entity_id' => $request->id,
            'tour_id' => $id,
            'host_id' => auth()->id(),
        ]);

        if ($insert) {
            return back()->with('success', 'Place added this tour successfully');
        }
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

    public function search($keyword, $tour_id)
    {
        $hotels = Property::query()
            ->from('properties as p')
            ->select('p.id', 'p.name')
            ->leftJoin(DB::raw("(select entity_type, entity_id, tour_id from tour_items where entity_type = 'place' and tour_id = $tour_id) as ti"),
                'ti.entity_id', '=', 'p.id')
            ->leftJoin('tours as t', 't.id', '=', 'ti.tour_id');

        if ($keyword != 'null') {
            $hotels = $hotels->where(DB::raw('LOWER(p.name)'), 'like', '%'.$keyword.'%');
        }
        $hotels = $hotels->whereNull('t.id')->orderBy('name')->get();

        return response()->json([
            'data' => $hotels,
        ]);
    }
}
