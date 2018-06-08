<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\User;

class RegisterConfirmationController extends Controller
{
    public function index(Request $request)
    {

        $user = User::where('confirmation_token', request('token'))->first();

        if (!$user) {

            return redirect('/threads')->with('flash', 'Wrong token');
        }

        $user->confirm();

        return redirect('/threads')->with('flash', 'You\'r now confirmed' );
    }
}
