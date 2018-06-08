<?php

namespace Tests\Feature;

use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

use App\Mail\PleaseConfirmYourEmail;
use App\User;

class RegistrationTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function a_confirmation_email_is_sent_upon_registration()
    {

        Mail::fake();

        event(new Registered(create('App\User')));

        Mail::assertSent(PleaseConfirmYourEmail::class);
    }

    /** @test */
    public function a_user_can_fully_confirm_their_email_adress()
    {

        Mail::fake();

        $this->post("/register", [
            'name' => 'JohnDoe',
            'email' => 'JohnDoe@gmail.com',
            'password' => '123456',
            'password_confirmation' => '123456',
        ]);

        $user = User::whereName('JohnDoe')->first();

        $this->assertFalse($user->confirmed);

        $this->assertNotNull($user->confirmation_token);

        $this->get("/register/confirm?token={$user->confirmation_token}");

        $this->assertTrue($user->fresh()->confirmed);
        $this->assertNull($user->fresh()->confirmation_token);
    }

    /** @test */
    public function confirming_invalid_token()
    {
        Mail::fake();

        $this->post("/register", [
            'name' => 'JohnDoe',
            'email' => 'JohnDoe@gmail.com',
            'password' => '123456',
            'password_confirmation' => '123456',
        ]);

        $user = User::whereName('JohnDoe')->first();

        $this->assertFalse($user->confirmed);

        $user->confirmation_token = str_random(25);
        $this->assertNotNull($user->confirmation_token);

        $this->get("/register/confirm?token={$user->confirmation_token}")
            ->assertRedirect('/threads')
            ->assertSessionHas('flash', 'Wrong token')
        ;

        $this->assertFalse($user->confirmed);

    }
}
