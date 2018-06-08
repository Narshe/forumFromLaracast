<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class FavoritesTest extends TestCase
{

    use DatabaseMigrations;

    /** @test */
    public function unauthenticated_user_cannot_like_anything()
    {
        $this->expectException('Illuminate\Auth\AuthenticationException');

        $reply = create('App\Reply');

        $this->post("replies/{$reply->id}/favorites");

    }

    /** @test */
    public function an_authenticated_user_can_like_a_reply()
    {
        $this->signIn();

        $reply = create('App\Reply');

        $this->post("replies/{$reply->id}/favorites");

        $this->assertCount(1, $reply->favorites);
    }

    /** @test */
    public function an_authenticated_user_can_unfavorited_a_reply()
    {
        $this->signIn();

        $reply = create('App\Reply');

        $this->post("replies/{$reply->id}/favorites");

        $this->assertCount(1, $reply->favorites);

        $this->delete("replies/{$reply->id}/favorites");

        $this->assertCount(0, $reply->fresh()->favorites);
    }

    /** @test */
    public function an_authenticated_user_may_only_favorite_a_reply_once()
    {
        $this->signIn();

        $reply = create('App\Reply');

        $this->post("replies/{$reply->id}/favorites");
        $this->post("replies/{$reply->id}/favorites");

        $this->assertCount(1, $reply->favorites);
    }
}
