<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class BestReplyTest extends TestCase
{
    use DatabaseMigrations;


    /** @test */
    public function a_thread_creator_may_mark_a_reply_as_best_reply()
    {
        $this->signIn();

        $thread = create('App\Thread', ['user_id' => auth()->id()]);

        $replies = create('App\Reply', [
            'thread_id' => $thread->id
        ],2);

        $this->postJson(route('best-reply.store', [$replies[1]->id]));

        $this->assertTrue($replies[1]->fresh()->isBest());
        $this->assertFalse($replies[0]->isBest());
    }

    /** @test */
    public function only_the_thread_creator_may_mark_the_reply_as_best()
    {
        $this->signIn();

        $thread = create('App\Thread');

        $replies = create('App\Reply', [
            'thread_id' => $thread->id
        ],2);

        $this->expectException('Illuminate\Auth\Access\AuthorizationException');
        $this->postJson(route('best-reply.store', [$replies[1]->id]));

    }

    /** @test */
    public function if_a_best_reply_is_deleted_set_to_null_best_reply_id_for_thread()
    {
        $this->signIn();


        $reply = create('App\Reply', [
            'user_id' => auth()->id()
        ]);

        $reply->thread->markBestReply($reply);

        $this->assertEquals($reply->id, $reply->thread->best_reply_id);

        $this->deleteJson("/replies/{$reply->id}");

        $this->assertDatabaseMissing('replies', ['id' => $reply->id]);

        // Foreign key constraint doesn't work with multiple migration files
        // $this->assertNull($reply->thread->fresh()->best_reply_id);
    }
}
