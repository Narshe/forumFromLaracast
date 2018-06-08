<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class LockThreadTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function unauthorized_user_cannot_lock_thread()
    {
        $this->signIn();

        $thread = create('App\Thread', ['user_id' => auth()->id()]);

        $this->expectException('Symfony\Component\HttpKernel\Exception\HttpException');
        $this->post("locked-threads/{$thread->id}");

    }

    /** @test */
    public function administrator_can_lock_thread()
    {
        $this->signIn(factory('App\User')->states('admin')->create());

        $thread = create('App\Thread', ['user_id' => auth()->id()]);

        $this->post("locked-threads/{$thread->id}");

        $this->assertTrue($thread->fresh()->isLocked());
    }

    /** @test */
    public function administrator_can_unlock_thread()
    {
        $this->signIn(factory('App\User')->states('admin')->create());

        $thread = create('App\Thread', ['user_id' => auth()->id()]);

        $this->post("locked-threads/{$thread->id}");

        $this->assertTrue($thread->fresh()->isLocked());

        $this->delete("locked-threads/{$thread->id}");

        $this->assertFalse($thread->fresh()->isLocked());
    }

    /** @test */
    public function once_lock_a_thread_cannot_receive_replies()
    {

        $this->signIn(factory('App\User')->states('admin')->create());

        $thread = create('App\Thread');

        $reply = make('App\Reply', [
            'thread_id' => $thread->id,
            'user_id' => auth()->id()
        ]);


        $this->post("locked-threads/{$thread->id}");

        $this->post("/threads/{$thread->channel->slug}/{$thread->id}/replies", $reply->toArray())
            ->assertStatus(422)
        ;

        $this->assertDatabaseMissing('replies', ['body' => $reply->body]);
        $this->assertTrue($thread->fresh()->isLocked());

    }
}
