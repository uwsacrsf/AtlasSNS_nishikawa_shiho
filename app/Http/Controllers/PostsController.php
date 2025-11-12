<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Post;

class PostsController extends Controller
{
    //
    public function index()
    {
        $user = Auth::user();
        if (!$user) {
            return redirect('/login');
        }

        $followingUserIds = $user->followings->pluck('id')->toArray();

        $displayUserIds = array_merge([$user->id], $followingUserIds);

        $posts = Post::whereIn('user_id', $displayUserIds)
                      ->with('user')
                      ->orderBy('created_at', 'desc')
                      ->get();

        $followingCount = $user->followings()->count();
        $followerCount = $user->followers()->count();


        return view('posts.index', compact('user', 'followingCount', 'followerCount','posts'));
    }
    public function store(Request $request)
    {
        $request->validate([
            'post' => 'required|string|max:150|min:1',
        ]);

        Post::create([
            'user_id' => Auth::id(),
            'post' => $request->post,
        ]);

        return redirect()->back();
    }

    public function editData(Post $post)
    {
        if ($post->user_id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        return response()->json([
            'id' => $post->id,
            'post' => $post->post,
        ]);
    }
    public function update(Request $request, Post $post)
    {
        if ($post->user_id !== Auth::id()) {
            return redirect()->back();
        }

        $request->validate([
            'post' => 'required|string|max:150|min:1',
        ]);

        $post->update([
            'post' => $request->post,
        ]);

        return redirect()->back();
    }
    public function destroy(Post $post)
    {
        if ($post->user_id !== Auth::id()) {
            return redirect()->back();
        }
        $post->delete();

        return redirect()->back();
    }
}
