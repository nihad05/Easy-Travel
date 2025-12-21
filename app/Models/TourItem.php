<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TourItem extends Model
{
    use HasFactory;

    /**
     * @var array
     */
    protected $fillable = [
        'entity_type',
        'entity_id',
        'host_id',
        'tour_id',
    ];

    public function hotel()
    {
        return $this->hasOne(Property::class, 'id', 'entity_id');
    }

    public function guide()
    {
        return $this->hasOne(User::class, 'id', 'entity_id')->where('role', 'guide');
    }

    public function hotels()
    {
        return $this->hasMany(Property::class, 'id', 'entity_id');
    }
}
