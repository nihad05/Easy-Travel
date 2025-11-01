<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Selection extends Model
{
    use HasFactory;

    protected $table = 'selections';

    protected $guarded = [];

    public function users()
    {
        return $this->belongsToMany(User::class, 'id');
    }
}
