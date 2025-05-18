<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\{HasMany, HasOne};

class Place extends Model
{
    use HasFactory;

    /**
     * @var array
     */
    protected $fillable = [
        'name',
        'about',
        'safety',
        'fun',
        'internet',
        'price',
        'location'
    ];

    /**
     * @return HasMany
     */
    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class, 'entity_id')->where('entity_type', 'place');
    }

    /**
     * @return HasOne
     */
    public function selections(): HasOne
    {
        return $this->hasOne(Selection::class, 'entity_id', 'id')
            ->where('entity_type', 'place')->where('user_id', auth()->id());
    }

    public function homeImage(): HasOne
    {
        return $this->hasOne(PlaceFiles::class, 'place_id', 'id')
            ->where('show_home', 1);
    }

    public function getImageAttribute()
    {
        return $this->homeImage?->image;
    }
}
