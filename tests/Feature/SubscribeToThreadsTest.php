<?php

namespace Tests\Feature;

use Tests\TestCase;

use Illuminate\Foundation\Testing\DatabaseMigrations;

class SubscribeToThreadsTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function an_authenticated_user_can_subscribe_a_thread()
    {

        $this->signIn();

        $thread = create('App\Thread');

        $this->post("/threads/{$thread->channel->slug}/{$thread->id}/subscriptions");

        $this->assertDatabaseHas('thread_subscriptions', ['thread_id' => $thread->id, 'user_id' => auth()->id()]);

        // $thread->addReply([
        //     'user_id'  => auth()->id(),
        //     'body' => "A new reply"
        // ]);
        //
        // $notifications = $thread->notifications()->where('user_id', auth()->id())->get();
        //
        // $this->assertTrue($notifications->contains($thread->id));
    }


    /** @test */
    public function an_authenticated_user_can_unsubscribe_from_a_thread()
    {

        $this->signIn();

        $thread = create('App\Thread');

        $this->post("/threads/{$thread->channel->slug}/{$thread->id}/subscriptions");
        $this->delete("/threads/{$thread->channel->slug}/{$thread->id}/subscriptions");

        $this->assertEquals(0, $thread->subscriptions()->where('user_id', auth()->id())->count());
    }
}
