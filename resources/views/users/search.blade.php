<x-login-layout>

    <form class="search-form" action="{{ route('users.search') }}" method="GET">
        <div class="search-head">
        <input class="search-input" type="text" name="keyword" placeholder="ユーザー名"
               value="{{ old('keyword', $keyword ?? '') }}"></input>
        <img src="images/search.png" type="submit"></img>
        </div>
        @if (!empty($keyword))
            <div class="search-zone">
                <p>
                    検索ワード: <strong>{{ $keyword }}</strong>
                </p>
            </div>
        @endif
    </form>

    <div class="search-result">
            <ul>
                @foreach ($users as $user)
                    <li>
                        <a href="{{ route('users.showProfile', $user) }}">
                            <img src="{{ asset('images/' . ($user->icon_image ?? 'default_profile_icon.png')) }}"
                                 alt="{{ $user->username }}のアイコン">
                            <span>{{ $user->username }}</span>
                        </a>
                        @auth
                @if (Auth::id() !== $user->id)
                @if (Auth::user()->isFollowing($user))
                        <form action="{{ route('users.unfollow', $user) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button class="search-unfollow" type="submit">フォロー解除</button>
                        </form>
                    @else
                        <form action="{{ route('users.follow', $user) }}" method="POST">
                            @csrf
                            <button class="search-tofollow" type="submit">フォローする</button>
                        </form>
                    @endif
                @endif
            @endauth
                    </li>
                @endforeach
            </ul>
    </div>
</x-login-layout>
