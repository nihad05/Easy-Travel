<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class BookProperty extends Model
{
    use HasFactory;

    protected $table = 'book_properties';

    protected $guarded = [];

    public function hotel()
    {
        return $this->hasOne(Property::class, 'id', 'hotel_id');
    }

    public function person()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function scopeExpired($query)
    {
        $today = Carbon::now();
        $todayFormatted = $today->toDateString();

        return $query->where('end_date', '<', $todayFormatted);
    }
}
