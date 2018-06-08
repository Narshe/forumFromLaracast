<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Redis;

class ThreadTest extends TestCase
{
    use DatabaseMigrations;

    protected $thread;

    public function setUp()
    {
        parent::setUp();

        $this->thread = create('App\Thread');
    }
    /** @test */
    public function a_thread_has_replies()
    {

        $this->assertInstanceOf('Illuminate\Database\Eloquent\Collection', $this->thread->replies);
    }

    public function a_thread_has_a_owner()
    {

        $this->assertInstanceOf('App\User', $this->thread->owner);
    }

    /** @test */
    public function a_thread_can_add_a_reply()
    {

        $this->thread->addReply([
            'body' => 'Foobar',
            'user_id' => 1
        ]);

        $this->assertCount(1, $this->thread->replies);
    }

    /** @test */
    public function a_thread_notifies_all_registered_subscribers_when_a_reply_is_added()
    {
        $this->signIn();

        $this->thread->subscribe();

        $this->thread->addReply([
            'body' => 'Foobar',
            'user_id' => create('App\User')->id
        ]);

        $this->assertEquals(1,auth()->user()->unreadNotifications()->count());
    }

    /** @test */
    public function a_thread_belongs_to_a_channel()
    {

        $this->assertInstanceOf('App\Channel', $this->thread->channel);
    }

    /** @test */
    public function a_thread_can_be_subscibed_to()
    {
        $this->signIn();

        $this->thread->subscribe();

        $this->assertEquals(1,$this->thread->subscriptions()->where('user_id', auth()->id())->count());

    }

    /** @test */
    public function a_thread_can_be_unsubscibed_from()
    {
        $this->signIn();

        $this->thread->subscribe();
        $this->thread->unsubscribe();

        $this->assertEquals(0,$this->thread->subscriptions()->where('user_id', auth()->id())->count());

    }

    /** @test */
    public function it_knows_if_the_current_user_is_subscribed_to_the_thread()
    {

        $this->signIn();

        $this->thread->subscribe();

        $this->assertTrue($this->thread->isSubscribed);
    }

    /** @test */
    public function a_thread_can_check_if_an_authenticated_user_has_read_all_replies()
    {
        $this->signIn();

        $this->assertTrue($this->thread->hasUpdatesFor());

        auth()->user()->read($this->thread);

        $this->assertFalse($this->thread->hasUpdatesFor());
    }

    /** @test */
    public function a_thread_can_record_visits()
    {

        $thread = make('App\Thread', ['id' => 1]);

        $thread->visits()->reset();

        $this->assertEquals(0, $thread->visits()->count());

        $thread->visits()->record();

        $this->assertEquals(1, $thread->visits()->count());
    }

    /** @test */
    public function a_thread_body_is_sanatized_automatically()
    {
        $thread = make('App\Thread', ['body' => '<script>alert("bad")</script><p>This is ok</p>']);

        $this->assertEquals("<p>This is ok</p>", $thread->body);
    }

}
