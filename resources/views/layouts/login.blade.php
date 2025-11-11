<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <!--IEブラウザ対策-->
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="description" content="ページの内容を表す文章" />
  <title></title>
  <link rel="stylesheet" href="{{ asset('css/reset.css') }} ">
  <link rel="stylesheet" href="{{ asset('css/app.css') }}">
  <link rel="stylesheet" href="{{ asset('css/style.css') }} ">
  <!--スマホ,タブレット対応-->
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <!-- Scripts -->
  <!--サイトのアイコン指定-->
  <link rel="icon" href="画像URL" sizes="16x16" type="image/png" />
  <link rel="icon" href="画像URL" sizes="32x32" type="image/png" />
  <link rel="icon" href="画像URL" sizes="48x48" type="image/png" />
  <link rel="icon" href="画像URL" sizes="62x62" type="image/png" />
  <!--iphoneのアプリアイコン指定-->
  <link rel="apple-touch-icon-precomposed" href="画像のURL" />
  <!--OGPタグ/twitterカード-->
</head>

<body>
@props(['followingCount' => 0, 'followerCount' => 0])
  <header>
    @include('layouts.navigation')
  </header>
  <!-- Page Content -->
  <div id="row">
    <div id="container">
      {{ $slot }}
    </div>
    <div id="side-bar">
      <div id="confirm">
        <p class="sidebar-username">{{ Auth::user()->username }}さんの</p>
        <div class="sidebar-item">
         <div class="sidebar-count-info">
          <p>フォロー数</p>
          <p>{{ $followingCount }}名</p>
         </div>
         <p class="sidebar-button-wrapper"><a href="{{ route('users.followList') }}" class="sidebar-button sidebar-button-block">フォローリスト</a></p>
        </div>
      <div class="sidebar-item">
        <div class="sidebar-count-info">
          <p>フォロワー数</p>
          <p>{{ $followerCount }}名</p>
        </div>
        <p class="sidebar-button-wrapper"><a href="{{ route('users.followerList') }}" class="sidebar-button sidebar-button-block">フォロワーリスト</a></p>
      </div>
      </div>
      <p class="sidebar-button-wrapper"><a href="{{ route('users.search') }}" class="sidebar-button search-button">ユーザー検索</a></p>
    </div>
  </div>
  <footer>
  </footer>
  <script src="{{ asset('js/app.js') }}"></script>
  <script src="{{ asset('js/script.js') }}"></script>
  <script src="JavaScriptファイルのURL"></script>
</body>

</html>
