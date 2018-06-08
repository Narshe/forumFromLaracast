<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;


use App\User;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;


    public function signIn(User $user = null)
    {
        $user = $user ?: create('App\User');
        $this->be($user);

        return $this;
    }
}
