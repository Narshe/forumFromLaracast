<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

use Illuminate\Notifications\DatabaseNotification;

class NotificationsTest extends TestCase
{

    use DatabaseMigrations;

    public function setUp()
    {
        parent::setUp();

        $this->signIn();
    }

    /** @test */
    public function a_notification_is_prepared_when_a_subscribed_thread_receives_a_new_reply_by_an_other_user()
    {


        $user = create('App\User');


        $thread = create('App\Thread');

        $thread->subscribe();

        $thread->addReply([
            'user_id'  => auth()->id(),
            'body' => "A new reply"
        ]);

        $this->assertCount(0, auth()->user()->notifications);

        $thread->addReply([
            'user_id'  => $user->id,
            'body' => "A new reply"
        ]);

        $this->assertCount(1, auth()->user()->fresh()->notifications);
    }

    /** @test */
    public function a_user_can_mark_a_notification_as_read()
    {

        $user = auth()->user();

        create(DatabaseNotification::class);

        $this->assertCount(1, $user->unreadNotifications);

        $notification = $user->unreadNotifications->first();

        $this->delete("/profiles/{$user->name}/notifications/{$notification->id}");

        $this->assertCount(0, $user->fresh()->unreadNotifications);
    }

    /** @test */
    public function a_user_can_fetch_their_unread_notification()
    {

        $user = auth()->user();

        create(DatabaseNotification::class);

        $response = $this->getJson("/profiles/{$user->name}/notifications")->json();

        $this->assertCount(1, $response);

    }
}
