<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'location',
        'role',
        'image',
        'confirm_code',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    public function requests()
    {
        return $this->hasOne(Request::class, 'user_id', 'id');
    }
    public function guides()
    {
        return $this->hasOne(GuideInfo::class, 'user_id', 'id');
    }
    public function blogs()
    {
        return $this->hasMany(Blog::class, 'user_id', 'id');
    }
    public function comments()
    {
        return $this->hasMany(Comment::class, 'entity_id')->where('entity_type', 'guide');
    }
    public function selections()
    {
        return $this->hasOne(Selection::class, 'entity_id', 'id')->where('entity_type', 'guide')->where('user_id', auth()->id());
    }
    public function items()
    {
        return $this->hasMany(Selection::class, 'user_id')->where('entity_type', 'place');
    }
    public function favProperty()
    {
        return $this->hasMany(Selection::class, 'user_id')->where('entity_type', 'property');
    }
    public function favGuide()
    {
        return $this->hasMany(Selection::class, 'user_id')->where('entity_type', 'guide');
    }
    protected $with = ['guides'];

    public function tour_guide()
    {
        return $this->hasOne(TourItem::class, 'entity_id', 'id');
    }
}
