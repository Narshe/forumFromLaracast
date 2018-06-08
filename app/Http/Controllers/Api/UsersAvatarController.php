<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\User;

class UsersAvatarController extends Controller
{


    public function store(User $user)
    {
        request()->validate([
            'avatar' => 'image|required'
        ]);

        auth()->user()->update([
            'avatar_path' => request('avatar')->store('avatar', 'public')
        ]);

        return response([], 204);
    }
}
