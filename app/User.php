<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    protected $casts = [
        'confirmed' => 'boolean'
    ];
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'avatar_path', 'confirmation_token', 'confirmed'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function getRouteKeyname()
    {
        return 'name';
    }

    public function threads()
    {
        return $this->hasMany('App\Thread')->latest();
    }

    public function activities()
    {
        return $this->hasMany('App\Activity');
    }

    public function visitedThreadCacheKey($thread)
    {
        return sprintf("user.%s.visits.%s", $this->id, $thread->id);
    }

    public function read($thread)
    {
        cache()->forever($this->visitedThreadCacheKey($thread), \Carbon\Carbon::now());
    }

    public function lastReply()
    {
        return $this->hasOne('App\Reply')->latest();
    }

    public function getAvatarPathAttribute($avatar)
    {
        return asset($avatar ? "/storage/{$avatar}" : 'storage/avatar/default.jpg');
    }

    public function confirm()
    {
        $this->confirmed = true;
        $this->confirmation_token = null;

        $this->save();
    }

    public function isAdmin()
    {
        return $this->name === 'Hirz';
    }
}
