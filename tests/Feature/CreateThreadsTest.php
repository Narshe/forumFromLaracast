<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

use App\Activity;

class CreateThreadsTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function unauthenticated_user_may_not_create_a_thread()
    {
        $this->expectException('Illuminate\Auth\AuthenticationException');

        $response = $this->post('/threads', []);

    }

    /** @test */
    public function authenticated_user_may_confirm_their_mail_adress_before_create_a_thread()
    {
        $user = factory('App\User')->states('unconfirmed')->create();

        $this->signIn($user);

        $thread = make('App\Thread');

        $this->post('/threads', $thread->toArray())
            ->assertRedirect('/threads')
            ->assertSessionHas('flash',"You must confirm your email adress")
        ;
    }

    /** @test */
    public function an_authenticated_user_may_create_a_thread()
    {

        $this->signIn();

        $thread = make('App\Thread');

        $this->post('/threads', $thread->toArray());

        $response = $this->get('/threads/'.$thread->id);
        $response
            ->assertSee($thread->title)
            ->assertSee($thread->body)
        ;
    }

    /** @test */
    public function an_authenticated_user_may_see_thread_form()
    {
        $this->signIn();

        $response = $this->get('/threads/create');

        $response
            ->assertStatus(200)
            ->assertSee('<form method="POST" action="/threads">')
        ;
    }

    /** @test */
    public function a_thread_require_a_title_and_body_to_be_updated()
    {
        $this->signIn();

        $thread = create('App\Thread', ['user_id' => auth()->id()]);

        $this->expectException("Illuminate\Validation\ValidationException");

        $this->patch("/threads/{$thread->id}", [
            'title' => "",
            'body' => 'Un nouveau body',
        ]);

    }

    /** @test */
    public function an_authenticated_user_cannot_update_an_other_thread()
    {

        $this->signIn();

        $thread = create('App\Thread');

        $this->expectException("Illuminate\Auth\Access\AuthorizationException");
        
        $this->patch("/threads/{$thread->id}", [
            'title' => "Un nouveau titre",
            'body' => 'Un nouveau body',
        ]);

    }

    /** @test */
    public function an_authenticated_user_may_update_his_thread()
    {
        $this->signIn();

        $thread = create('App\Thread', ['user_id' => auth()->id()]);

        $this->patch("/threads/{$thread->id}", [
            'title' => "Un nouveau titre",
            'body' => 'Un nouveau body',
        ]);

        $this->assertEquals('Un nouveau body', $thread->fresh()->body);
        $this->assertEquals('Un nouveau titre', $thread->fresh()->title);

    }

    /** @test */
    public function unauthenticated_user_may_not_see_thread_form()
    {
        $this->expectException('Illuminate\Auth\AuthenticationException');

        $response = $this->get('/threads/create');

    }

    /** @test */
    public function a_thread_require_a_title()
    {
        $this->expectException('Illuminate\Validation\ValidationException');

        $this->publishThread(['title' => null]);

    }

    /** @test */
    public function a_thread_require_a_body()
    {
        $this->expectException('Illuminate\Validation\ValidationException');

        $this->publishThread(['body' => null]);

    }

    /** @test */
    public function a_thread_require_a_valid_channel()
    {

        $this->expectException('Illuminate\Validation\ValidationException');

        $channels = factory('App\Channel', 2)->create();

        $this->publishThread(['channel_id' => null]);

    }

    /** @test */
    public function a_thread_require_an_existing_channel()
    {

        $this->expectException('Illuminate\Validation\ValidationException');

        $channels = factory('App\Channel', 2)->create();

        $this->publishThread(['channel_id' => 999]);

    }

    /** @test */
    public function authorized_thread_can_be_deleted()
    {

        $this->signIn();

        $thread = create('App\Thread', ['user_id' => auth()->id()]);
        $reply = create('App\Reply', ['thread_id' => $thread->id]);

        $this->delete("/threads/{$thread->channel->slug}/{$thread->id}");

        $this->assertDatabaseMissing('threads', ['id' => $thread->id]);
        $this->assertDatabaseMissing('replies', ['id' => $reply->id]);

        $this->assertEquals(0, Activity::count());
    }

    /** @test */
    public function unauthorized_may_not_delete_thread()
    {

        $user = create('App\User');
        $thread = create('App\Thread', ['user_id' => $user->id]);

        $this->expectException('Illuminate\Auth\AuthenticationException');
        $this->delete("/threads/{$thread->channel->slug}/{$thread->id}");

        $this->signIn();
        $this->delete("/threads/{$thread->channel->slug}/{$thread->id}");

        $this->assertDatabaseHas('threads', ['id' => $thread->id]);


    }


    public function threads_may_only_be_deleted_by_those_who_have_permission()
    {

        $user = create('App\User');
        $thread = create('App\Thread', ['user_id' => $user->id]);

        $this->expectException('Illuminate\Auth\AuthenticationException');

    }



    private function publishThread($param = [])
    {
        $this->signIn();

        $thread = make('App\Thread', $param);
        return $this->post('/threads', $thread->toArray());
    }
}
