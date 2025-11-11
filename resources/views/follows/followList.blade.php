<x-login-layout>
<div class="following-head">
    <h2>フォローリスト</h2>

    <div class="following-icons">
            @foreach ($followings as $followedUser)
                <a href="{{ route('users.showProfile', $followedUser) }}">
                        <img src="{{ asset('images/' . ($followedUser->icon_image ?? 'default_profile_icon.png')) }}">
                    </a>
            @endforeach
    </div>
</div>
    <div class="post-list">
            <ul>
                @foreach ($followingsPosts as $post)
                    <li>
                        <a href="{{ route('users.showProfile', $post->user) }}">
                            <img src="{{ asset('images/' . ($post->user->icon_image ?? 'default_profile_icon.png')) }}"
                                 alt="{{ $post->user->username }}のアイコン">
                        </a>
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
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

</x-login-layout>
