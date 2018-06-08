<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

use App\Activity;

class AddAvatarsTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function guest_cannot_upload_avatars()
    {
        $user = create('App\User');

        $this->expectException('Illuminate\Auth\AuthenticationException');

        $this->post("/api/users/{$user->name}/avatar");
    }

    /** @test */
    public function a_valid_avatar_must_be_provided()
    {
        $this->signIn();

        $user = auth()->user();

        $this->expectException('Illuminate\Validation\ValidationException');

        $this->post("/api/users/{$user->name}/avatar", [
            'avatar' => 'not-an-image'
        ]);
    }

    /** @test */
    public function an_authenticated_user_can_upload_an_avatar()
    {
        $this->signIn();
        $user = auth()->user();

        Storage::fake('public');

        $this->post("/api/users/{$user->name}/avatar", [
            'avatar' => $file = UploadedFile::fake()->image('avatar.jpg')
        ]);

        $this->assertEquals(asset("storage/avatar/{$file->hashName()}"), $user->avatar_path);

        Storage::disk('public')->assertExists("avatar/{$file->hashName()}");
    }
}
