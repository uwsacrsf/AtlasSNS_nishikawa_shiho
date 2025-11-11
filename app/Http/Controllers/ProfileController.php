<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Illuminate\Support\Facades\Storage; // ファイル操作に必要
use Illuminate\Support\Facades\Hash;    // パスワードハッシュ化に必要
use Illuminate\Validation\Rule;         // Rule::unique を使うために必要
use Illuminate\Http\RedirectResponse;   // 戻り値の型ヒントに必要

class ProfileController extends Controller
{
    /**
     * プロフィール編集フォームを表示
     */
    public function edit(): View
    {
        $user = Auth::user(); // ログインユーザーを取得
        return view('profiles.profile', compact('user'));
    }

    /**
     * プロフィール情報を更新（ユーザー名、メールアドレス、パスワード、自己紹介、アイコン画像含む）
     */
    public function update(Request $request): RedirectResponse
    {
        $user = Auth::user(); // ログインユーザーを取得

        // ★★★ バリデーションルール ★★★
        // パスワード関連のフィールドは両方nullableにして、カスタムロジックでバリデーションを厳しくします。
        $rules = [
            'username' => ['required', 'string', 'min:2', 'max:12', Rule::unique('users')->ignore($user->id)],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'bio' => ['nullable', 'string', 'max:150'], // bioは空でも良い場合
            'icon_image_file' => ['nullable', 'image', 'mimes:jpeg,png,bmp,gif,svg', 'max:2048'], // max:2048KB (2MB)を追加
            // current_password と password_confirmation にも最小・最大長を適用
            'current_password' => ['nullable', 'string', 'min:8', 'max:20'],
            'password_confirmation' => ['nullable', 'string', 'min:8', 'max:20'],
        ];

        // フォームデータがバリデーションルールを通過
        // バリデーションエラーがあれば、自動的に前のページに戻ります
        $validatedData = $request->validate($rules);


        // ★★★ ユーザー情報の更新（パスワード以外） ★★★
        $user->username = $validatedData['username'];
        $user->email = $validatedData['email'];
        $user->bio = $validatedData['bio'] ?? null; // bioがnullableの場合に備える


        // ★★★ パスワード更新ロジック（2つの入力欄対応） ★★★
        $mainPasswordInput = $request->input('current_password'); // Bladeのname="current_password"
        $confirmPasswordInput = $request->input('password_confirmation'); // Bladeのname="password_confirmation"

        // Case 1: 両方のパスワード欄が空の場合（パスワード変更なし）
        if (empty($mainPasswordInput) && empty($confirmPasswordInput)) {
            // 何もしない（パスワード変更スキップ）
        }
        // Case 2: 片方だけ入力されている場合
        else if (empty($mainPasswordInput) || empty($confirmPasswordInput)) {
            // エラーメッセージを 'current_password' フィールドに紐付ける
            return back()->withErrors(['current_password' => 'パスワードとその確認入力の両方を入力してください。']);
        }
        // Case 3: 両方入力されている場合
        else {
            // 入力されたパスワードが一致しない場合
            if ($mainPasswordInput !== $confirmPasswordInput) {
                // エラーメッセージを 'current_password' フィールドに紐付ける
                return back()->withErrors(['current_password' => 'パスワードと確認入力が一致しません。']);
            }

            // ここに到達した時点で、mainPasswordInput と confirmPasswordInput は一致している

            // 入力されたパスワードが現在のパスワードと一致するかどうかをチェック
            if (Hash::check($mainPasswordInput, $user->password)) {
                // 入力されたパスワードが現在のパスワードと一致する場合
                // これはパスワード変更なしを意味するので、何もしない（パスワードは更新しない）
            }
            // 入力されたパスワードが現在のパスワードと異なる場合
            // これは新しいパスワードとして設定する
            else {
                $user->password = Hash::make($mainPasswordInput);
            }
        }
        // ★★★ パスワード更新ロジックここまで ★★★


       // ★★★ アイコン画像のアップロード処理 ★★★
       if ($request->hasFile('icon_image_file')) {
       // 古いアイコンがあれば削除（デフォルトアイコンは削除しない）
       if ($user->icon_image && $user->icon_image !== 'default_profile_icon.png') {
        $oldImagePath = public_path('images/' . $user->icon_image);
        if (file_exists($oldImagePath)) {
            unlink($oldImagePath);
        }
    }
    // 新しい画像のファイル名を生成
    $fileName = time() . '.' . $request->file('icon_image_file')->extension();
    // 画像をpublic/imagesディレクトリに保存
    $request->file('icon_image_file')->move(public_path('images'), $fileName);
    // データベースにファイル名だけを保存
    $user->icon_image = $fileName;
}

        // データベースに保存
        $user->save();

        // プロフィール更新後、TOPページへ遷移
        return redirect()->route('top');
    }
}
