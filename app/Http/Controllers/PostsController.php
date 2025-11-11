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

        // 自分のIDも表示対象に含める
        $displayUserIds = array_merge([$user->id], $followingUserIds);

        // ★★★ 自分の投稿とフォローしているユーザーの投稿のみ取得 ★★★
        $posts = Post::whereIn('user_id', $displayUserIds) // 対象ユーザーのIDリストで絞り込み
                      ->with('user') // 投稿に紐づくユーザー情報も Eager Loading で取得
                      ->orderBy('created_at', 'desc') // 新しい順に並べ替え
                      ->get(); // 投稿を取得

        $followingCount = $user->followings()->count();
        $followerCount = $user->followers()->count();


        return view('posts.index', compact('user', 'followingCount', 'followerCount','posts'));
    }
    public function store(Request $request)
    {
        $request->validate([
            'post' => 'required|string|max:150|min:1',
        ]);

        // 投稿をデータベースに保存
        Post::create([
            'user_id' => Auth::id(),
            'post' => $request->post,
        ]);

        return redirect()->back();
    }

    public function editData(Post $post) // Route Model Binding で自動的にPostインスタンスが注入される
    {
        // 自分の投稿のみ編集可能にする
        if ($post->user_id !== Auth::id()) {
            // abort(403); // 403 Forbidden エラーを返す
            return response()->json(['error' => 'Unauthorized'], 403); // JSONでエラーを返す
        }

        // 投稿データをJSON形式で返す
        return response()->json([
            'id' => $post->id,
            'post' => $post->post, // 投稿内容
            // 他に編集したいカラムがあれば追加
        ]);
    }
    public function update(Request $request, Post $post) // Route Model Binding で自動的にPostインスタンスが注入される
    {
        // 自分の投稿のみ編集可能にする
        if ($post->user_id !== Auth::id()) {
            return redirect()->back();
        }

        // バリデーション
        $request->validate([
            'post' => 'required|string|max:150|min:1',
        ]);

        // 投稿を更新
        $post->update([
            'post' => $request->post,
        ]);

        return redirect()->back();
    }
    public function destroy(Post $post) // Route Model Binding で自動的にPostインスタンスが注入される
    {
        // ★★★ 自分の投稿のみ削除可能にする ★★★
        if ($post->user_id !== Auth::id()) {
            return redirect()->back();
        }

        // ★★★ 投稿を削除する処理 ★★★
        $post->delete();

        // 削除後に元のページにリダイレクトし、成功メッセージをフラッシュ
        return redirect()->back();
    }
}
