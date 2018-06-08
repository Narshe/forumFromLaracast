<?php

namespace App;

use Illuminate\Support\Facades\Redis;

use App\Thread;

class Trending
{
    public function get() {

        return array_map('json_decode', Redis::zrevrange($this->cacheKey(), 0, 4));
    }

    public function push(Thread $thread) {

        Redis::zincrby($this->cacheKey(), 1, json_encode([
            'title' => $thread->title,
            'path' => "/threads/{$thread->channel->slug}/{$thread->id}"
        ]));
    }

    public function cacheKey() {

        return 'trending_threads';
    }

    public function reset() {

        Redis::del($this->cacheKey());
    }
}
