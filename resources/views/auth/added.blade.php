
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AtlasSNS ログイン</title>
    <link rel="stylesheet" href="{{ asset('css/reset.css') }}">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <!-- Tailwind CSSを使用している場合、またはViteを使用している場合は不要な場合があります -->
    <!-- <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet"> -->
</head>
<body class="login-body">
    <div class="gradient-background">
    <header class="login-header">
            <h1 class="site-title">Atlas</h1>
            <p class="site-subtitle">Social Network Service</p>
        </header>
        <section class="login-section">
            <div class="login-container">
            <h2 class="add-message">{{ $username }}さん
              <br>
           ようこそ！AtlasSNSへ！</h2>
           <p>ユーザー登録が完了しました。</p>
           <p>早速ログインをしてみましょう。</p>
           <a href="{{ route('login') }}" class="login-button">ログイン画面へ</a>
            </div>
        </section>
    </div>
</body>
</html>
