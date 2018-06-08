<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class MentionUsersTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function mentions_user_in_a_reply_are_notified()
    {

        $johnDoe = create('App\User', ['name' => 'JohnDoe']);
        $janeDoe = create('App\User', ['name' => 'JaneDoe']);

        $this->signIn($johnDoe);

        $thread = create('App\Thread');

        $reply = make('App\Reply', [
            'user_id' => $johnDoe->id,
            'thread_id' => $thread->id,
            'body' => 'Hello @JaneDoe'
        ]);

        $this->post(
            "/threads/{$thread->channel->slug}/{$thread->id}/replies",
            $reply->toArray()
        );

        $this->assertCount(1, $janeDoe->notifications);


    }

    /** @test */
    public function it_can_fetch_all_mentioned_users_starting_with_the_given_characters()
    {

        create('App\User', ['name' => 'johnDoe']);
        create('App\User', ['name' => 'janeDOe']);
        create('App\User', ['name' => 'johnDoe2']);

        $results = $this->json('GET', '/api/users', ['name' => 'john']);

        $this->assertCount(2, $results->json());
    }
}
