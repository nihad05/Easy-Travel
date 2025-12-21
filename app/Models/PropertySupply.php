<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PropertySupply extends Model
{
    use HasFactory;

    protected $table = 'property_supplies';

    protected $fillable = [
        'property_id',
        'supply_id',
    ];
}
