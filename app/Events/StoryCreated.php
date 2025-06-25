<?php

namespace App\Events;

use App\Models\Story;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class StoryCreated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * The story instance.
     * @var \App\Models\Story
     */
    public Story $story;

    /**
     * Loại file: 'image', 'video', hoặc 'none'.
     * @var string
     */
    public string $fileType;

    /**
     * URL của file chính (ảnh hoặc video).
     * @var string|null
     */
    public ?string $fileUrl = null;

    /**
     * URL của ảnh thumbnail (nếu có).
     * @var string|null
     */
    public ?string $thumbnailUrl = null;


    /**
     * Create a new event instance.
     *
     * @param  \App\Models\Story  $story
     * @return void
     */
    public function __construct(Story $story)
    {
        // Tải sẵn thông tin người dùng để gửi đi kèm
        $this->story = $story->load('user');

        // Lấy loại file từ story, đây là thông tin quan trọng nhất
        $this->fileType = $this->story->file_type ?? 'none';

        // Chỉ xử lý URL nếu story có file đính kèm (không phải 'none')
        if ($this->fileType !== 'none' && $this->story->image) {
            
            // Lấy URL của file gốc
            $this->fileUrl = Storage::url($this->story->image);

            // Xử lý tạo thumbnail URL nếu là ảnh
            // Nếu bạn không có logic tạo ảnh thumb, bạn có thể bỏ qua phần này
            // hoặc gán thumbnailUrl bằng chính fileUrl.
            if ($this->fileType === 'image') {
                $this->thumbnailUrl = $this->fileUrl; // Đơn giản nhất là dùng ảnh gốc làm thumbnail
            }
            // Đối với video, bạn có thể để thumbnail là null, 
            // và ở frontend sẽ hiển thị một icon video mặc định.
            // Hoặc nếu bạn có logic tạo ảnh poster cho video, bạn sẽ gán URL ở đây.
        }
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new Channel('stories'),
        ];
    }
}