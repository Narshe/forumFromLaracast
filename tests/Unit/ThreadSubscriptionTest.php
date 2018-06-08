<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ThreadSubscriptionTest extends TestCase
{
    use DatabaseMigrations;


    /** @test */
    public function it_has_an_user()
    {

        $thread = create('App\Thread');

        $this->signIn();
        $thread->subscribe();

        $this->assertInstanceOf('App\User', $thread->subscriptions[0]->user);
    }
}
