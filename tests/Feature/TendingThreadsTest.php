<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Redis;

use App\Trending;

class TendingThreadsTest extends TestCase
{
    use DatabaseMigrations;

    protected $trending;

    public function __construct() {

        parent::__construct();

        $this->trending = new Trending();
    }

    public function setUp()
    {
        parent::setUp();

        $this->trending->reset();
    }

    /** @test */
    public function it_increment_a_threads_score_each_time_it_is_read()
    {

        $this->assertCount(0, $this->trending->get());

        $thread = create('App\Thread');

        $this->get("/threads/{$thread->channel->slug}/{$thread->id}");

        $trending = $this->trending->get();

        $this->assertCount(1,$trending);

        $this->assertEquals($thread->title, $trending[0]->title);
    }
}
