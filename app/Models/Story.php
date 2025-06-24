<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model; // <-- Dòng này đã được use ở trên
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

// SỬA DÒNG NÀY
class Story extends Model // <-- KHÔNG PHẢI 'extends Story'
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'content',
        'image',
    ];

    /**
     * Quan hệ Story thuộc về một User
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Quan hệ Story có nhiều Comment
     */
    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class)->latest();
    }
}