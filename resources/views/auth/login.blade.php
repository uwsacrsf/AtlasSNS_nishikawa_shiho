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
        <section class="login-section">
            <div class="login-container">
                <h2 class="welcome-message">AtlasSNSへようこそ</h2>

                <form method="POST" action="{{ route('login') }}" class="login-form">
                    @csrf

                    <div class="form-group">
                        <label for="email">メールアドレス</label>
                        <input type="email" name="email" id="email" value="{{ old('email') }}" required autofocus>
                        @error('email')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="password">パスワード</label>
                        <input type="password" name="password" id="password" required autocomplete="current-password">
                        @error('password')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="login-button">ログイン</button>

                    <a href="{{ route('register') }}" class="register-link">新規ユーザーの方はこちら</a>
                </form>
            </div>
        </section>
    </div>
</body>
</html>
