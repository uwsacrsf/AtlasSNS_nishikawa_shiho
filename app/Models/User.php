<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'username',
        'email',
        'password',
        'icon_image',
        'bio',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function followings(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'follows', 'following_id', 'followed_id');
    }
    public function followers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'follows', 'followed_id', 'following_id');
    }
    /**
     * 特定のユーザーをフォローしているかチェックするヘルパーメソッド
     * ★★★ このメソッドがこの場所（class User { ... } の中）に正確に書かれているか確認！ ★★★
     */
    public function isFollowing(User $user): bool
    {
        return $this->followings()->where('followed_id', $user->id)->exists();
    }
    /**
     * このユーザーが行った投稿を取得
     */
    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }
}
