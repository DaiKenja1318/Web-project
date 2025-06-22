<?php

namespace App\Events;

use App\Models\Comment;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CommentCreated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $comment;

    public function __construct(Comment $comment)
    {
        // Eager load a user relationship to avoid N+1 problem on the client side
        $this->comment = $comment->load('user');
    }

    public function broadcastOn(): array
    {
        // Broadcast trên kênh riêng của story đó.
        // Chỉ những ai đang xem story này mới nhận được comment.
        return [
            new PrivateChannel('stories.' . $this->comment->story_id),
        ];
    }
}