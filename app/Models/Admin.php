<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Autenthicable;
use Spatie\Permission\Traits\HasRoles;

class Admin extends Autenthicable
{
    use HasFactory, HasRoles;

    protected $fillable = [
        'name', 'email', 'role',
    ];

    protected $hidden = [
        'password',
    ];
}
