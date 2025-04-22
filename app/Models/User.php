<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Laratrust\Contracts\LaratrustUser;
use Laratrust\Traits\HasRolesAndPermissions;
use Illuminate\Database\Eloquent\Relations\MorphToMany;


class User extends Authenticatable implements LaratrustUser
{
    use HasFactory, Notifiable, HasApiTokens, HasRolesAndPermissions;

    protected $table  = 'users';

    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'address',
        'image'
    ];

    protected $appends = [
        'picture_url'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'password' => 'hashed',
        'email_verified_at' => 'datetime',
    ];

    public function getPictureUrlAttribute()
    {
        $picture = $this->picture;
        return $picture ? asset('uploads/' . $picture) : asset('assets/images/user.png');
    }


    public function bookings()
    {
        return $this->hasMany(Booking::class, 'receptionist_id');
    }
}
