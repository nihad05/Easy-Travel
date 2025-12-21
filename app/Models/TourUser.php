<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TourUser extends Model
{
    use HasFactory;

    protected $table = 'tour_users';

    protected $guarded = [];

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function active()
    {
        return $this->hasOne(Tour::class, 'id', 'tour_id');
    }
}
