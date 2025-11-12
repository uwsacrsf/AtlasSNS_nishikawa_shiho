<x-login-layout :followingCount="$followingCount" :followerCount="$followerCount">
<form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PATCH')

    <div class="form-item-top">
    <img class="update-icon" src="{{ asset('images/' . $user->icon_image) }}">
     <div class="form-item">
        <label for="username">ユーザー名</label>
        <input type="text" name="username" id="username" value="{{ old('username', $user->username) }}" required></input>
        @error('username')
            <div class="alert alert-danger">{{ $message }}</div>
        @enderror
    </div>

        <div class="form-item">
            <label for="email">メールアドレス</label>
            <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" required></input>
        </div>

        <div class="form-item">
            <label for="current_password">パスワード</label>
            <input type="password" name="current_password" id="current_password" >
        </div>

        <div class="form-item">
            <label for="password_confirmation">パスワード確認</label>
            <input type="password" name="password_confirmation" id="password_confirmation">
        </div>

    <div class="form-item">
        <label for="bio">自己紹介</label>
        <input type="text" name="bio" id="bio" rows="3" value="{{ old('bio', $user->bio) }}" required></input>
    </div>

    <div class="form-item">
        <label for="icon_image_file">アイコン画像</label>
        <input type="file" name="icon_image_file" id="icon_image_file" accept="image/*">
    </div>

    <div class="form-item-button">
    <button type="submit">更新</button>
    </div>
</form>
</div>

</x-login-layout>
