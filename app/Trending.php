<?php

namespace App;

use Illuminate\Support\Facades\Redis;

use App\Thread;

class Trending
{
    /**
     * [Get the last 5 results from redis and sort them by their rank]
     * @return Array trending
     */
    public function get() {

        return array_map('json_decode', Redis::zrevrange($this->cacheKey(), 0, 4));
    }

    /**
     * [Increment by one the key cacheKey and set the correct thread data]
     * @param  Thread $thread
     */
    public function push(Thread $thread) {

        Redis::zincrby($this->cacheKey(), 1, json_encode([
            'title' => $thread->title,
            'path' => "/threads/{$thread->channel->slug}/{$thread->id}"
        ]));
    }

    /**
     * [cacheKey]
     * @return String
     */
    public function cacheKey() {

        return 'trending_threads';
    }

    /**
     * [reset]
     */
    public function reset() {

        Redis::del($this->cacheKey());
    }
}
