<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class UserTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function a_user_can_fetch_their_most_recent_reply()
    {

        $user = create('App\User');

        $this->signIn($user);

        $reply = create('App\Reply', ['user_id' => $user->id]);

    //    dd($user->lastReply);
        $this->assertEquals($user->lastReply->id, $reply->id);
    }

    /** @test */
    public function a_user_can_determine_their_avatar_path()
    {
        $user = create('App\User', ['avatar_path' => 'avatar/me.jpg']);
        $userWithOutAvatar = create('App\User');

        $this->assertEquals('http://localhost/storage/avatar/me.jpg', $user->avatar_path);
        $this->assertEquals('http://localhost/storage/avatar/default.jpg', $userWithOutAvatar->avatar_path);
    }
}
