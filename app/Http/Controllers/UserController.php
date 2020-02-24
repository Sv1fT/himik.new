<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\User;

class UserController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }

    public function profile()
    {
        $user = Auth::user()->attributes;
        return view('user.profile')->with('user');
    }

    public function favorites()
    {
        $user = User::with('favorite_adverts')->whereId(Auth::id())->first();
        $user = $user->favorite_adverts;
        return view('user.profile',compact('user'));
    }

    public function messages() 
    {
        $messages = 
    }
}
