<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Blog extends Model
{
    use HasFactory;

    protected $table = 'blogs';

    protected $guarded = [];

    public function author()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function category()
    {
        return $this->hasOne(BlogCategory::class, 'id', 'category_id');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class, 'entity_id')->where('entity_type', 'blog');
    }
}
