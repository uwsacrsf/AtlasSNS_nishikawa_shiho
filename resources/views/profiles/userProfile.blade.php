<x-login-layout :followingCount="$followingCount" :followerCount="$followerCount">

    <div class="user-profile">
        <img src="{{ asset('images/' . ($user->icon_image ?? 'default_profile_icon.png')) }}"
             alt="{{ $user->username }}のアイコン">

        <div class="user-profile-head">
            <div class="use-profile-row">
            <p class="user-profile-label">ユ-ザ-名</p>
            <p class="user-profile-value">{{ $user->username }}</p>
            </div>

            <div class="use-profile-row">
            <p class="user-profile-label">自己紹介</p>
            <p class="user-profile-text">{{ $user->bio }}</p>
            </div>

            @auth
                @if (Auth::id() !== $user->id)
                    @if ($isFollowing)
                        <form action="{{ route('users.unfollow', $user) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button class="unfollow" type="submit">フォロー解除</button>
                        </form>
                    @else
                        <form action="{{ route('users.follow', $user) }}" method="POST">
                            @csrf
                            <button class="tofollow" type="submit">フォローする</button>
                        </form>
                    @endif
                @endif
            @endauth
        </div>
    </div>

    {{-- ユーザーの投稿一覧 --}}
    <div class="post-list">
            <ul>
                @foreach ($posts as $post)
                    <li>
                        <img src="{{ asset('images/' . ($user->icon_image ?? 'default_profile_icon.png')) }}"
                             alt="{{ $user->username }}のアイコン">

                             <div class="post-content">
                            <div class="post-username">
                                <p>
                                    <strong>{{ $post->user->username }}</strong>
                                </p>
                                <span>
                                    {{ $post->created_at->format('Y-m-d H:i') }}
                                </span>
                            </div>

                            <p class="post-text">{{ $post->post }}</p>
                            </div>
                    </li>
                @endforeach
            </ul>
    </div>

</x-login-layout>
