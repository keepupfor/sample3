<?php

namespace App\Http\Controllers;

use Auth;
use App\Models\User;
use Illuminate\Http\Request;

class FollowersController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function store(User $user)
    {
        if (Auth::user()->id===$user->id)
        {
            return redirect('/');
        }
        if (!Auth::user()->isFollowing($user->id))
        {
            Auth::user()->follow($user->id);
        }
        return back()->with('success','关注用户成功');
    }
    public function destroy(User $user)
    {
        if (Auth::user()->id===$user->id)
        {
            return redirect('/');
        }
        if (Auth::user()->isFollowing($user->id))
        {
            Auth::user()->unfollow($user->id);
        }
        return back()->with('success','取消关注用户成功');
    }
}
