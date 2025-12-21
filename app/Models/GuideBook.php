<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GuideBook extends Model
{
    use HasFactory;

    protected $table = 'guide_books';

    protected $guarded = [];

    public function guide()
    {
        return $this->hasOne(User::class, 'id', 'guide_id');
    }

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }
}
