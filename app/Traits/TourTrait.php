<?php

namespace App\Traits;

use App\Models\Tour;

trait TourTrait
{
    public function prepareTourPlaces($places, $tourId)
    {
        $arr = [];

        foreach ($places as $item) {
            $arr[] = [
                'place_id' => $item,
                'tour_id' => $tourId,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        return $arr;
    }

    public function prepareTourTransport($transports, $tourId)
    {
        $arr = [];

        foreach ($transports as $item) {
            $arr[] = [
                'transport_id' => $item,
                'tour_id' => $tourId,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        return $arr;
    }

    public function tourCheck($id)
    {
        $tourPlan = Tour::query()
            ->where('id', $id)
            ->where('host_id', auth()->id())
            ->first();

        if (! $tourPlan) {
            abort(404);
        }

        return $tourPlan;
    }
}
