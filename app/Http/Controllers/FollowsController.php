<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class FollowsController extends Controller
{
    public function followList(){
        $user = Auth::user();
        $followingCount = $user->follows()->count();
        $followerCount = $user->followers()->count();

        $followings = $user->follows()->latest()->get();

        return view('follows.followList', [
            'followingCount' => $followingCount,
            'followerCount' => $followerCount,
            'followings' => $followings,
        ]);
    }

    public function followerList(){
        $user = Auth::user();

        $followingCount = $user->follows()->count();
        $followerCount = $user->followers()->count();

        $followers = $user->followers()->latest()->get();

        return view('follows.followerList', [
            'followingCount' => $followingCount,
            'followerCount' => $followerCount,
            'followers' => $followers,
        ]);
    }

    public function follow(User $user)
    {
        $follower = Auth::user();

        if ($follower->id === $user->id) {
            return back();
        }

        if (!$follower->isFollowing($user)) {
            $follower->followings()->attach($user->id);
            return back();
        }

        return back();
    }

    public function unfollow(User $user)
    {
        $follower = Auth::user();

        if ($follower->id === $user->id) {
            return back();
        }

        if ($follower->isFollowing($user)) {
            $follower->followings()->detach($user->id);
            return back();
        }

        return back();
    }
}
