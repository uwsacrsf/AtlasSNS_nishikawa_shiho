<x-login-layout :followingCount="$followingCount" :followerCount="$followerCount">
<div class="post-form-container">
<img class="top-icon" src="{{ asset('images/' . $user->icon_image) }}">
  <form action="{{ route('posts.store') }}" method="POST">
    @csrf
    <textarea name="post" placeholder="投稿内容を入力してください。"></textarea>
    <button type="submit" class="icon-button post-button-icon"></button>
  </form>
  </div>

  <div class="post-list">
    <ul>
        @forelse ($posts as $post)
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

            <div class="post-buttons">
            @if ($post->user_id === Auth::id())
                <button class="edit-post-btn icon-button edit-icon" data-post-id="{{ $post->id }}"></button>
                <form action="{{ route('posts.destroy', $post) }}" method="POST" onsubmit="return confirm('この投稿を削除します。よろしいでしょうか？');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="icon-button delete-icon"></button>
                </form>
            @endif
        </div>
        </li>
        @empty
        <li class="p-4 text-gray-500 text-center">まだ投稿はありません。</li>
    @endforelse
    </ul>
</div>
<hr>

  <div id="editPostModal" class="modal">
    <div class="modal-content">
        <form id="editPostForm" method="POST">
            @csrf
            @method('PATCH')

            <input type="hidden" name="post_id" id="editPostId">
            <textarea name="post" id="editPostContent" rows="5"></textarea>

            @error('post', 'update')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror

            <button type="submit" class="edit-button"></button>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const modal = document.getElementById('editPostModal');
        const closeButton = document.querySelector('.close-button');
        const editButtons = document.querySelectorAll('.edit-post-btn');
        const editPostForm = document.getElementById('editPostForm');
        const editPostIdField = document.getElementById('editPostId');
        const editPostContentField = document.getElementById('editPostContent');

        editButtons.forEach(button => {
            button.addEventListener('click', function() {
                const postId = this.dataset.postId;

                fetch(`/posts/${postId}/edit_data`)
                    .then(response => {
                        if (!response.ok) {
                            if (response.status === 403) {
                                alert('他のユーザーの投稿は編集できません。');
                            } else {
                                alert('投稿データの取得に失敗しました。');
                            }
                            throw new Error('Network response was not ok.');
                        }
                        return response.json();
                    })
                    .then(data => {
                        editPostIdField.value = data.id;
                        editPostContentField.value = data.post;
                        editPostForm.action = `/posts/${data.id}`;
                        modal.style.display = 'block';
                    })
                    .catch(error => {
                        console.error('Error:', error);
                    });
            });
        });

        closeButton.addEventListener('click', function() {
            modal.style.display = 'none';
        });

        window.addEventListener('click', function(event) {
            if (event.target == modal) {
                modal.style.display = 'none';
            }
        });
    });
</script>

</x-login-layout>
