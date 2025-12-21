<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

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
        'location',
    ];

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class, 'entity_id')->where('entity_type', 'place');
    }

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
