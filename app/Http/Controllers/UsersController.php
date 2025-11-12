<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;

class UsersController extends Controller
{
    public function search(Request $request)
    {
        $keyword = $request->input('keyword');

        $currentUserId = Auth::id();

        $query = User::where('id', '!=', $currentUserId);

        if ($keyword) {
            $users = $query->where('username', 'like', '%' . $keyword . '%')
                           ->orderBy('username', 'asc')
                           ->get();
        }
        else {
            $users = $query->orderBy('username', 'asc')->get();
        }
        return view('users.search', compact('keyword', 'users'));
    }
        /**
     * ログインユーザーがフォローしているユーザーのリストを表示する
     *
     * @return \Illuminate\View\View
     */
    public function followList()
    {
        $user = Auth::user();

        $followings = $user->followings()->get();

        $followingIds = $user->followings()->pluck('users.id');

        $followingsPosts = Post::with('user')
                               ->whereIn('user_id', $followingIds)
                               ->orderBy('created_at', 'desc')
                               ->get();

        return view('follows.followList', compact('followings','followingsPosts'));
    }

    /**
     * ログインユーザーをフォローしているユーザーのリストとその投稿リストを表示する
     *
     * @return \Illuminate\View\View
     */
    public function followerList()
    {
        $user = Auth::user();

        $followers = $user->followers()->get();

        $followerIds = $user->followers()->pluck('users.id');

        $followersPosts = Post::with('user')
                               ->whereIn('user_id', $followerIds)
                               ->orderBy('created_at', 'desc')
                               ->get();

        return view('follows.followerList', compact('followers', 'followersPosts'));
    }

    public function index()
    {
        $user = Auth::user();

        if (!$user) {
            return redirect('/login');
        }
        $users = User::where('id', '!=', Auth::id())
                     ->orderBy('username', 'asc')
                     ->get();

        return view('users.index', compact('users'));
    }
/**
     * 指定されたユーザーをフォローする
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function follow(User $user)
    {
        $authUser = Auth::user();

        if ($authUser->id === $user->id) {
            return back();
        }

        if (!$authUser->isFollowing($user)) {
            $authUser->followings()->attach($user->id);
            return back();
        }
        return back();
    }

    /**
     * 指定されたユーザーのフォローを解除する
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function unfollow(User $user)
    {
        $authUser = Auth::user();

        if ($authUser->isFollowing($user)) {
            $authUser->followings()->detach($user->id);
            return back();
        }

        return back();
    }
    /**
     * 指定されたユーザーのプロフィールページを表示する
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\View\View
     */
    public function showProfile(User $user)
    {
        $posts = $user->posts()->orderBy('created_at', 'desc')->get();

        $loggedInUser = Auth::user();
        $isFollowing = false;

        if ($loggedInUser && $loggedInUser->id !== $user->id) {
            $isFollowing = $loggedInUser->isFollowing($user);
        }

        return view('profiles.userProfile', compact('user', 'posts', 'isFollowing'));
    }
}
