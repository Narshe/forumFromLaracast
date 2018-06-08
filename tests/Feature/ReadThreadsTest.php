<?php

namespace Tests\Feature;

use Tests\TestCase;

use Illuminate\Foundation\Testing\DatabaseMigrations;


class ReadThreadsTest extends TestCase
{
    use DatabaseMigrations;

    protected $thread;

    public function setUp()
    {
        parent::setUp();

        $this->thread = create('App\Thread');

    }

    /** @test */
    public function a_user_can_browser_all_threads()
    {

        $response = $this->get('/threads');

        $response
            ->assertStatus(200)
            ->assertSee($this->thread->title)
        ;
    }

    /** @test */
    public function a_user_can_read_a_single_thread()
    {


        $response = $this->get(
            "/threads/{$this->thread->channel->slug}/{$this->thread->id}"
        );

        $response->assertSee($this->thread->title);
    }

    /** @test */
    public function a_user_can_filter_threads_according_to_a_tag()
    {
        $channel = create('App\Channel');
        $threadInChannel = create('App\Thread', ['channel_id' => $channel->id]);
        $threadNotInChannel = create('App\Thread');

        $response = $this->get("/threads/{$channel->slug}");

        $response
            ->assertSee($threadInChannel->title)
            ->assertDontSee($threadNotInChannel->title)
        ;
    }

    /** @test */
    public function a_user_can_filter_threads_by_any_username()
    {
        $user = create('App\User', ['name' => 'JohnDoe']);

        $this->signIn($user);

        $threadByJohn = create('App\Thread', ['user_id' => auth()->id()]);
        $threadNotByJohn = create('App\Thread');

        $response = $this->get('/threads?by=JohnDoe');

        $response
            ->assertSee($threadByJohn->title)
            ->assertDontSee($threadNotByJohn->title)
        ;
    }

    /** @test */
    public function a_user_can_filter_threads_by_popularity()
    {

        $threadWithTwoReplies = create('App\Thread');
        create('App\Reply', ['thread_id' => $threadWithTwoReplies->id],2);

        $threadWithThreeReplies = create('App\Thread');
        create('App\Reply', ['thread_id' => $threadWithThreeReplies->id],3);

        $threadWithNoReplies = $this->thread;

        $response = $this->getJson('/threads?popularity=1')->json();

        $this->assertEquals([3,2,0],array_column($response['data'], 'replies_count'));
    }

    /** @test */
    public function a_user_can_filter_unanswered_threads()
    {

        $threadWithReplies = create('App\Thread');
        $replies = create('App\Reply', ['thread_id' => $threadWithReplies->id]);

        $response = $this->get('/threads?unanswered=1');

        $response->assertSee($this->thread->title);
        $response->assertDontSee($threadWithReplies->title);

    }

    /** @test */
    public function a_user_can_request_all_replies_for_a_given_thread()
    {

        $thread = create('App\Thread');
        $reply = create('App\Reply', ['thread_id' => $thread->id],2);
        $channel = $thread->channel;

        $response = $this->get("/threads/{$channel}/{$thread->id}/replies")->json();

        $this->assertEquals($response["data"][0]["body"], $reply[0]->body);
    }
}
