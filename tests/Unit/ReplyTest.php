<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

use Carbon\Carbon;

class ReplyTest extends TestCase
{

    use DatabaseMigrations;

    /** @test */
    public function it_has_an_owner()
    {
        $reply = create('App\Reply');

        $this->assertInstanceOf('App\User', $reply->owner);

    }

    /** @test */
    public function check_if_a_reply_is_not_favorited_by_a_user()
    {

        $reply = create('App\Reply');

        $this->assertFalse($reply->isFavorited());

    }

    /** @test */
    public function check_if_a_reply_is_already_favorited_by_a_user()
    {

        $this->signIn();
        $reply = create('App\Reply');

        $this->post("/replies/{$reply->id}/favorites");

        $this->assertTrue($reply->isFavorited());

    }

    /** @test */
    public function check_if_a_reply_was_just_published_by_a_user()
    {


        $reply = create('App\Reply');

        $this->assertTrue($reply->wasJustPublished());

        $reply = create('App\Reply', ['created_at' => Carbon::now()->subMonth()]);

        $this->assertFalse($reply->wasJustPublished());
    }

    /** @test */
    public function check_if_a_user_is_mentioned_in_a_reply()
    {

        $reply = new \App\Reply([
            'body' => 'Hello @JohnDoe'
        ]);

        $users = $reply->mentionedUsers();
        $this->assertEquals(['JohnDoe'], $reply->mentionedUsers());

    }

    /** @test */
    public function it_wrap_mentioned_usernames_in_the_body_with_anchor_tag()
    {
        $reply = new \App\Reply([
            'body' => 'Hello @JohnDoe.'
        ]);


        $this->assertEquals(
            'Hello <a href="/profiles/JohnDoe">@JohnDoe</a>.',
            $reply->body
        );
    }

    /** @test */
    public function it_knows_if_it_is_the_best_reply()
    {
        $reply = create('App\Reply');

        $this->assertFalse($reply->isBest());

        $reply->thread->update(['best_reply_id' => $reply->id]);

        $this->assertTrue($reply->fresh()->isBest());
    }
}
