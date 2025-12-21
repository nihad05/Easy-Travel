<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class PlaceFiles extends Model
{
    use HasFactory;

    /**
     * @var array
     */
    protected $fillable = [
        'image',
        'place_id',
        'show_home',
    ];

    public function place(): HasOne
    {
        return $this->hasOne(Place::class, 'id', 'place_id');
    }
}
