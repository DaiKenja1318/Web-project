<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Comment extends Model
{
    use HasFactory;

    /**
     * Các thuộc tính được phép gán hàng loạt.
     *
     * @var array
     */
    protected $fillable = [
        'story_id',
        'user_id',
        'content',
    ];

    /**
     * Mối quan hệ: Một Comment thuộc về một Story.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function story(): BelongsTo
    {
        return $this->belongsTo(Story::class);
    }

    /**
     * Mối quan hệ: Một Comment thuộc về một User (người viết comment).
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}