<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ParticipateInForumTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function unauthenticated_user_may_not_participate_to_a_forum_thread()
    {
        $thread = create('App\Thread');
        $this->expectException('Illuminate\Auth\AuthenticationException');

        $this->post(
            "/threads/{$thread->channel->slug}/$thread->id/replies",
            []
        );

    }

    /** @test */
    public function an_authenticated_user_may_participate_to_a_forum_thread()
    {
        $this->signIn();

        $thread = create('App\Thread');
        $reply = make('App\Reply');

        $this->post(
            "/threads/{$thread->channel->slug}/{$thread->id}/replies",
            $reply->toArray()
        );

        $response = $this->get("/threads/{$thread->channel->slug}/{$thread->id}");

        $this->assertDatabaseHas('replies', ['body' => $reply->body]);
    }

    /** @test */
    public function a_reply_must_have_a_body()
    {

        $this->signIn();

        $thread = create('App\Thread');
        $reply = make('App\Reply', ['body' => null]);

        $this->expectException('Illuminate\Validation\ValidationException');

        $response = $this->post(
            "/threads/{$thread->channel->slug}/{$thread->id}/replies",
            $reply->toArray()
        );

    //    $this->assertDatabaseMissing('replies', ['body' => $reply->body]);

    }

    /** @test */
    public function guest_cannot_delete_a_reply()
    {
        $reply = create('App\Reply');

        $this->expectException('Illuminate\Auth\AuthenticationException');

        $response = $this->delete(
            "/replies/{$reply->id}"
        );

    }

    /** @test */
    public function unauthorized_user_cannot_delete_a_reply()
    {
        $this->signIn();
        $reply = create('App\Reply');

        $this->expectException("Illuminate\Auth\Access\AuthorizationException");

        $response = $this->delete(
            "/replies/{$reply->id}"
        );

        $this->assertDatabaseHas('replies', ['id' => $reply->id]);
    }


    /** @test */
    public function an_authorized_user_can_delete_a_reply()
    {

        $this->signIn();

        $reply = create('App\Reply', ['user_id' => auth()->id()]);

        $response = $this->delete(
            "replies/{$reply->id}"
        );

        $this->assertDatabaseMissing('replies', ['id' => $reply->id]);
    }

    /** @test */
    public function an_authorized_user_can_edit_a_reply()
    {
        $this->signIn();

        $reply = create('App\Reply', ['user_id' => auth()->id()]);

        $this->patch("/replies/{$reply->id}", ['body' => "Updated Reply"]);

        $this->assertDatabaseHas('replies', ['id' => $reply->id, 'body' => 'Updated Reply']);
    }

    /** @test */
    public function guest_user_can_edit_a_reply()
    {

        $reply = create('App\Reply');

        $this->expectException('Illuminate\Auth\AuthenticationException');

        $this->patch("/replies/{$reply->id}", ['body' => "Updated Reply"]);

    }

    /** @test */
    public function unauthorized_user_can_edit_a_reply()
    {

        $this->signIn();
        $reply = create('App\Reply');

        $this->expectException('Illuminate\Auth\Access\AuthorizationException');

        $this->patch("/replies/{$reply->id}", ['body' => "Updated Reply"]);

    }

    /** @test */
    public function replies_that_contains_spam_may_not_be_created()
    {
        $this->signIn();

        $thread = create('App\Thread');
        $reply = make('App\Reply', [
            'body' => 'Yahoo Customer Support'
        ]);

        $this->expectException('Illuminate\Validation\ValidationException');
        $response = $this->post(
            "/threads/{$thread->channel->slug}/{$thread->id}/replies",
            $reply->toArray()
        );

    }

    /** @test */
    public function users_may_only_reply_a_maximum_of_once_per_minute()
    {

        $user = create('App\User');

        $this->signIn($user);

        $thread = create('App\Thread');

        $reply = make('App\Reply', ['user_id' => $user->id]);
        $replyBeforeOneMinute = make('App\Reply', ['user_id' => $user->id]);


        $response = $this->post(
            "/threads/{$thread->channel->slug}/{$thread->id}/replies",
            $reply->toArray()
        );

        $this->expectException(\Exception::class);

        $response = $this->post(
            "/threads/{$thread->channel->slug}/{$thread->id}/replies",
            $replyBeforeOneMinute->toArray()
        );

        $this->assertDatabaseMissing('replies', ['body' => $replyBeforeOneMinute->body]);

    }
}
