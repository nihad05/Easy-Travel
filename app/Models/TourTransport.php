<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TourTransport extends Model
{
    use HasFactory;

    /**
     * @var string[]
     */
    protected $fillable = [
        'tour_id',
        'place_id',
    ];
}
