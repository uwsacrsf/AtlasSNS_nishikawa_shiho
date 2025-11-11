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
        $keyword = $request->input('keyword'); // 検索キーワードを取得

        // ログインしているユーザーのIDを取得
        $currentUserId = Auth::id();

        // 検索クエリのベースを作成（自分のユーザーは常に除外）
        $query = User::where('id', '!=', $currentUserId);

        // キーワードが入力されている場合（検索時）
        if ($keyword) {
            $users = $query->where('username', 'like', '%' . $keyword . '%')
                           ->orderBy('username', 'asc') // ユーザー名をアルファベット順に並べ替え
                           ->get();
        }
        // キーワードが入力されていない場合（初期表示時）
        else {
            $users = $query->orderBy('username', 'asc')->get(); // 自分以外の全ユーザーを取得
        }

        // ビューにキーワードと検索結果（または全ユーザーリスト）を渡す
        return view('users.search', compact('keyword', 'users'));
    }
        /**
     * ログインユーザーがフォローしているユーザーのリストを表示する
     *
     * @return \Illuminate\View\View
     */
    public function followList()
    {
        // 現在認証されているユーザーを取得
        $user = Auth::user();


        // ログインユーザーがフォローしているユーザーのコレクションを取得
        // Userモデルに定義した followings() リレーションを使用
        $followings = $user->followings()->get();

        // ログインユーザーがフォローしているユーザーのIDを取得
        $followingIds = $user->followings()->pluck('users.id');

        $followingsPosts = Post::with('user')
                               ->whereIn('user_id', $followingIds)
                               ->orderBy('created_at', 'desc')
                               ->get();

        // ビューにフォローしているユーザーのリストを渡す
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

        // ログインユーザーをフォローしているユーザーのコレクション（ユーザー情報全体）を取得
        // アイコン一覧表示用
        $followers = $user->followers()->get(); // ★★★ followings() から followers() に変更 ★★★

        // ログインユーザーをフォローしているユーザーのIDを取得
        $followerIds = $user->followers()->pluck('users.id'); // ★★★ followings() から followers() に変更 ★★★

        // フォローされているユーザーの投稿を取得
        // with('user') で投稿に紐づくユーザー情報もまとめて取得（N+1問題対策）
        // whereIn() で、上記で取得したフォロワーユーザーIDに合致する投稿を絞り込み
        // orderBy('created_at', 'desc') で新しい投稿から表示
        $followersPosts = Post::with('user')
                               ->whereIn('user_id', $followerIds)
                               ->orderBy('created_at', 'desc')
                               ->get();

        // ビューにフォロワーユーザーのリストと投稿リストの両方を渡す
        return view('follows.followerList', compact('followers', 'followersPosts')); // ★★★ ビュー名と変数名を変更 ★★★
    }

    /**
     * 登録されているユーザーの一覧を表示する（このメソッドは、もし /users ルートで別の全ユーザー一覧ページを表示したい場合に残します）
     */
    public function index()
    {
        // 現在ログインしているユーザーを取得
        $user = Auth::user();

        // もしユーザーがログインしていなければ、リダイレクトなどの処理
        if (!$user) {
            return redirect('/login'); // 例
        }

        // ログインユーザー以外の全ユーザーを取得
        // 自分自身を除外したい場合はこの条件を追加します
        $users = User::where('id', '!=', Auth::id())
                     ->orderBy('username', 'asc') // ユーザー名をアルファベット順に並べ替え
                     ->get();

        // 取得したユーザーデータを 'users.index' ビューに渡す
        return view('users.index', compact('users'));
    }
/**
     * 指定されたユーザーをフォローする
     *
     * @param  \App\Models\User  $user  フォロー対象のユーザーモデル
     * @return \Illuminate\Http\RedirectResponse
     */
    public function follow(User $user)
    {
        // 現在認証されているユーザー（フォローを実行する側）
        $authUser = Auth::user();

        // 自分自身をフォローしようとした場合のガード
        if ($authUser->id === $user->id) {
            return back();
        }

        // 既にフォローしているかチェックし、まだフォローしていなければ追加
        // isFollowing() メソッドは User モデルに定義済みと仮定
        if (!$authUser->isFollowing($user)) {
            // followings() は User モデルで定義された BelongsToMany リレーション
            $authUser->followings()->attach($user->id); // 中間テーブルにレコードを追加
            return back();
        }

        // 既にフォローしている場合のメッセージ
        return back();
    }

    /**
     * 指定されたユーザーのフォローを解除する
     *
     * @param  \App\Models\User  $user  フォロー解除対象のユーザーモデル
     * @return \Illuminate\Http\RedirectResponse
     */
    public function unfollow(User $user)
    {
        // 現在認証されているユーザー（フォロー解除を実行する側）
        $authUser = Auth::user();

        // フォローしているかチェックし、フォローしていれば削除
        // isFollowing() メソッドは User モデルに定義済みと仮定
        if ($authUser->isFollowing($user)) {
            // followings() は User モデルで定義された BelongsToMany リレーション
            $authUser->followings()->detach($user->id); // 中間テーブルからレコードを削除
            return back();
        }

        // フォローしていない場合のメッセージ
        return back();
    }
    /**
     * 指定されたユーザーのプロフィールページを表示する
     *
     * @param  \App\Models\User  $user  表示対象のユーザーモデル
     * @return \Illuminate\View\View
     */
    public function showProfile(User $user) // 例: showProfile というメソッド名
    {
        // プロフィール表示対象のユーザーの投稿を取得
        // 投稿日時が新しい順に並べ替え
        $posts = $user->posts()->orderBy('created_at', 'desc')->get();

        // ログインユーザーが認証されているか確認
        $loggedInUser = Auth::user();
        $isFollowing = false; // デフォルトはフォローしていない

        // ログインユーザーが存在し、かつプロフィールページのユーザーが自分自身ではない場合
        if ($loggedInUser && $loggedInUser->id !== $user->id) {
            // ログインユーザーがこのプロフィールページのユーザーをフォローしているかチェック
            $isFollowing = $loggedInUser->isFollowing($user);
        }

        return view('profiles.userProfile', compact('user', 'posts', 'isFollowing'));
    }
}
