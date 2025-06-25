<?php

namespace App\Events;

use App\Models\Story;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage; // Thêm use này

class StoryCreated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Story $story;
    
    // THÊM CÁC THUỘC TÍNH MỚI
    public ?string $imageUrlLarge = null;
    public ?string $imageUrlMedium = null;
    public ?string $imageUrlThumb = null;

    public function __construct(Story $story)
    {
        $this->story = $story->load('user');

        // GÁN GIÁ TRỊ CHO CÁC URL
        if ($story->image) {
            $pathInfo = pathinfo($story->image);
            $directory = $pathInfo['dirname'];
            $filename = $pathInfo['basename'];

            $this->imageUrlLarge = Storage::url($story->image);
            $this->imageUrlMedium = Storage::url($directory . '/medium_' . $filename);
            $this->imageUrlThumb = Storage::url($directory . '/thumb_' . $filename);
        }
    }

    public function broadcastOn(): array
    {
        return [
            new Channel('stories'),
        ];
    }
}