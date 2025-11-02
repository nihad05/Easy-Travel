<?php

namespace App\Http\Controllers\Client\Tour;

use App\Http\Controllers\Controller;
use App\Models\TourItem;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GuideController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        $gudies = User::query()
            ->without('guides')
            ->from('users as u')
            ->select(
                'u.id as user_id',
                'u.name',
                'u.image',
                'gi.price',
                't.start_time',
                't.end_time',
                'u.location',
                'ti.id as item_id'
            )
            ->join('guide_infos as gi', 'u.id', 'gi.user_id')
            ->leftJoin(DB::raw("(select id, tour_id, entity_id, entity_type from tour_items WHERE entity_type = 'guide' and tour_id = $id) as ti"),
                'ti.entity_id', 'u.id')
            ->leftJoin('tours as t', 't.id', 'ti.tour_id')
            ->whereNull('t.id')
            ->where('u.role', 'guide')
            ->orderBy('name')
            ->get();

        return response([
            'data' => $gudies,
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

    public function store(Request $request, $id): RedirectResponse
    {
        $insert = TourItem::query()
            ->create([
                'entity_type' => 'guide',
                'entity_id' => $request->userId,
                'tour_id' => $id,
                'host_id' => auth()->id(),
            ]);
        if ($insert) {
            return back()->with('success', 'Guide added this tour successfully!');
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

    public function search($keyword, $id)
    {
        $guides = User::query()
            ->without('guides')
            ->from('users as u')
            ->select(
                'u.id as user_id',
                'u.name',
                'u.image',
                'gi.price',
                't.start_time',
                't.end_time',
                'u.location',
                'ti.id as item_id'
            )
            ->join('guide_infos as gi', 'u.id', 'gi.user_id')
            ->leftJoin(DB::raw("(select id, tour_id, entity_id, entity_type from tour_items WHERE entity_type = 'guide' and tour_id = $id) as ti"),
                'ti.entity_id', 'u.id')
            ->leftJoin('tours as t', 't.id', 'ti.tour_id')
            ->whereNull('t.id')
            ->where('u.role', 'guide');

        if ($keyword != null) {
            $guides = $guides->where('u.name', 'like', '%'.$keyword.'%');
        }

        $guides = $guides->orderBy('name')->get();

        return response([
            'data' => $guides,
        ], 200);
    }
}
