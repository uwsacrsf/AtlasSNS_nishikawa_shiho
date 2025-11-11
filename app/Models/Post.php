<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Post extends Model
{
    use HasFactory;

    /**
     * マスアサインメントを許可するカラムを指定
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id', // 投稿したユーザーのID
        'post', // 投稿内容
    ];

    /**
     * この投稿を所有するユーザーを取得
     */
    public function user(): BelongsTo // ★このメソッドを追加！
    {
        return $this->belongsTo(User::class);
    }
}
