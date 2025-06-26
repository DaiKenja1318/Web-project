<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use App\Models\Story;
use Illuminate\Support\Facades\Storage;

class StoryUpdatedEvent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Story $story;

    /**
     * Create a new event instance.
     */
    public function __construct(Story $story)
    {
        $this->story = $story;
    }

    /**
     * Get the channels the event should broadcast on.
     */
    public function broadcastOn(): array
    {
        return [
            // Phát sóng trên một kênh công khai tên là 'stories'
            new Channel('stories'),
        ];
    }

    /**
     * Đặt tên cho sự kiện khi gửi đi.
     */
    public function broadcastAs(): string
    {
        return 'story.updated';
    }

    /**
     * Lấy dữ liệu sẽ được phát sóng.
     */
    public function broadcastWith(): array
    {
        return [
            'id' => $this->story->id,
            'title' => $this->story->title,
            'content' => $this->story->content,
            // Gửi URL đầy đủ của ảnh để client có thể hiển thị
            'image_url' => $this->story->image ? Storage::url($this->story->image) : null,
            'updated_at' => $this->story->updated_at->toDateTimeString(),
        ];
    }
}