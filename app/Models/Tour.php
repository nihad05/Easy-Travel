<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tour extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'tours';

    protected $guarded = ['id'];

    public function hotels(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(TourItem::class, 'tour_id')->where('entity_type', 'place');
    }

    public function guides(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(TourItem::class, 'tour_id')->where('entity_type', 'guide');
    }

    public function host()
    {
        return $this->hasOne(User::class, 'id', 'host_id');
    }

    public function transports()
    {
        return $this->hasMany(TourTransport::class, 'tour_id', 'id')
            ->select('tour_transports.*', 't.name')
            ->join('transports as t', 't.id', '=', 'tour_transports.transport_id');
    }

    public function places()
    {
        return $this->hasMany(TourPlace::class, 'tour_id', 'id')
            ->select('tour_places.*', 'p.name', 'p.id as p_id')
            ->join('places as p', 'p.id', 'tour_places.place_id');
    }

    public function transaction()
    {
        return $this->hasOne(TourTransaction::class, 'tour_id', 'id');
    }

    public function users()
    {
        return $this->hasMany(TourUser::class, 'tour_id', 'id');
    }
}
