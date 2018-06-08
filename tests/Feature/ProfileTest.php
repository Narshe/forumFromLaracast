<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class Profiletest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function a_user_has_a_profile()
    {
        $user = create('App\User');
        //$this->signIn($user);

        $response = $this->get("/profiles/{$user->name}");

        $response->assertSee($user->name);

    }

    /** @test */
    public function a_profile_contains_threads_created_by_the_user_associated()
    {
        $user = create('App\User');
        $this->signIn($user);
        $thread = create('App\Thread', ['user_id' => $user->id]);

        $response = $this->get("/profiles/{$user->name}");

        $response
            ->assertSee($thread->title)
            ->assertSee($thread->body)
        ;
    }

}
