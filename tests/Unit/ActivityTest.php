<?php

namespace Tests\Unit;

use App\Activity;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Carbon\Carbon;

class ActivityTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function it_records_activity_when_a_thread_is_created()
    {
        $this->signIn();

        $user = auth()->user();

        $thread = create('App\Thread', ['user_id' => $user->id]);

        $this->assertDatabaseHas('activities', [
            'type' => 'created_thread',
            'user_id' => $user->id,
            'subject_id' => $thread->id,
            'subject_type' => 'App\Thread'
        ]);

        $activity = Activity::first();

        $this->assertEquals($activity->subject->id, $thread->id);

    }

    /** @test */
    public function it_records_activity_when_a_reply_is_created()
    {
        $this->signIn();

        $user = auth()->user();

        $reply = create('App\Reply', ['user_id' => $user->id]);

        $this->assertDatabaseHas('activities', [
            'type' => 'created_reply',
            'user_id' => $user->id,
            'subject_id' => $reply->id,
            'subject_type' => 'App\Reply'
        ]);

        $activity = Activity::where('subject_id', $reply->id)->first();

        $this->assertEquals($activity->subject->id, $reply->id);

    }

    /** @test */
    public function it_fetches_a_feed_for_any_user()
    {
        $user = create('App\User');
        $this->signIn($user);

        $threadFromOneWeekAgo = create('App\Thread', [
            'user_id' => $user->id,

        ]);

        $threadFromToday = create('App\Thread', ['user_id' => $user->id]);

        auth()->user()->activities()->first()->update(['created_at' => Carbon::now()->subWeek()]);

        $feed = Activity::feed($user);

        $this->assertTrue($feed->keys()->contains(
            $threadFromToday->created_at->format('Y-m-d')
        ));

        $this->assertEquals(
            $feed[Carbon::now()->format('Y-m-d')]
                ->first()
                ->subject
                ->title,
            $threadFromToday->title
        );

        $this->assertTrue($feed->keys()->contains(
            $threadFromOneWeekAgo->created_at->format('Y-m-d')
        ));

        $this->assertEquals(
            $feed[Carbon::now()->subWeek()->format('Y-m-d')]
                ->first()
                ->subject
                ->title,
            $threadFromOneWeekAgo->title
        );
    }
}
