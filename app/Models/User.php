<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable {
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.P
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'username',
        'email',
        'password',
    ];

    protected function getAvatar(): Attribute {
        return Attribute::make(get: function ($value) {
            if (auth()->user()->avatar !== '') {
                return '/storage/avatars/' . auth()->user()->avatar;
            }
            return '/storage/default.png';
        });
    }

    /**
     * The attributes that should be hidden for serialization.janaina 
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

    public function feedPosts() {
        return  $this->hasManyThrough(Post::class, Follow::class, 'user_id', 'user_id', 'id', 'followedUser');
    }

    public function isFollowing() {
        return $this->hasMany(Follow::class, 'user_id');
    }

    public function followers() {
        return $this->hasMany(Follow::class, 'followedUser');
    }

    public function posts() {
        return $this->hasMany(Post::class, 'user_id');
    }
}
