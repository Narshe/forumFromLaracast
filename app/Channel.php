<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Channel extends Model
{
    /**
     * [threads]
     * @return HasMany threads
     */
    public function threads()
    {
        return $this->hasMany('App\Thread');
    }

    public function getRouteKeyname()
    {
        return 'slug';
    }
}
