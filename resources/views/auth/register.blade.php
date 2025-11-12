<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AtlasSNS ログイン</title>
    <link rel="stylesheet" href="{{ asset('css/reset.css') }}">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">

</head>
<body class="login-body">
    <div class="gradient-background">
    <header class="login-header">
            <h1 class="site-title">Atlas</h1>
            <p class="site-subtitle">Social Network Service</p>
        </header>
<div class="login-container">
<h2 class="welcome-message">新規ユーザー登録</h2>

<form method="POST" action="{{ route('register') }}" class="login-form">
        @csrf

        <div class="form-group">
            <label for="username">ユーザー名</label>
            <input type="text" name="username" id="username" value="{{ old('username') }}" required>
            @error('username')
                <div class="error-message">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="email">メールアドレス</label>
            <input type="email" name="email" id="email" value="{{ old('email') }}" required>
            @error('email')
                <div class="error-message">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="password">パスワード</label>
            <input type="password" name="password" id="password" required>
            @error('password')
                <div class="error-message">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="password_confirmation">パスワード確認</label>
            <input type="password" name="password_confirmation" id="password_confirmation" required>
        </div>

        <button type="submit" class="login-button">新規登録</button>

        <a href="{{ route('login') }}" class="register-link">ログイン画面へ戻る</a>
    </form>
</div>
