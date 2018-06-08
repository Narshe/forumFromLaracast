<?php

namespace App;

use Illuminate\Support\Facades\Redis;


class Visits
{

    public function __construct($thread) {

        $this->thread = $thread;
    }

    /**
     * [reset]
     * @return App\Visits
     */
    public function reset()
    {
        Redis::del($this->cacheKey());

        return $this;
    }

    /**
     * [record]
     * @return App\Visits
     */
    public function record()
    {
        Redis::incr($this->cacheKey());

        return $this;
    }

    /**
     * [count]
     * @return Integer count
     */
    public function count()
    {
        return Redis::get($this->cacheKey()) ?: 0;
    }

    /**
     * [cacheKey]
     * @return String cacheKey
     */
    public function cacheKey()
    {
        return "threads.{$this->thread->id}.visits";
    }
}
