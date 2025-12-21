<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HostRequest extends Model
{
    use HasFactory;

    protected $table = 'host_requests';

    protected $guarded = [];

    public function tour()
    {
        return $this->hasOne(Tour::class, 'id', 'tours_id');
    }

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }
}
