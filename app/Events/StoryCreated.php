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

    // Giữ lại story object, rất hữu ích
    public Story $story;

    // --- THAY ĐỔI CÁC THUỘC TÍNH ĐỂ CHUNG CHUNG HƠN ---
    public string $fileType;      // Loại file: 'image', 'video', hoặc 'none'
    public ?string $fileUrl = null;      // URL của file gốc (ảnh lớn hoặc video)
    public ?string $thumbnailUrl = null; // URL của ảnh thumbnail (dùng cho cả ảnh và video)

    /**
     * Create a new event instance.
     */
    public function __construct(Story $story)
    {
        // Tải sẵn user để có thông tin người đăng
        $this->story = $story->load('user');

        // Gán loại file từ model, đây là điểm mấu chốt
        $this->fileType = $this->story->file_type;

        // Chỉ xử lý URL nếu có file được đính kèm
        if ($this->fileType !== 'none' && $this->story->image) {
            
            // Lấy URL của file chính
            $this->fileUrl = Storage::url($this->story->image);

            // Xử lý thumbnail
            if ($this->fileType === 'image') {
                // Giả sử bạn có tạo một phiên bản thumbnail cho ảnh
                // Nếu không, bạn có thể cho thumbnailUrl bằng fileUrl luôn
                $pathInfo = pathinfo($this->story->image);
                $thumbnailPath = $pathInfo['dirname'] . '/thumb_' . $pathInfo['basename'];
                
                // Chỉ gán nếu file thumbnail thực sự tồn tại
                if (Storage::disk('public')->exists($thumbnailPath)) {
                    $this->thumbnailUrl = Storage::url($thumbnailPath);
                } else {
                    $this->thumbnailUrl = $this->fileUrl; // Dùng ảnh gốc làm thumbnail nếu không có file thumb
                }

            } elseif ($this->fileType === 'video') {
                // Đối với video, thumbnail có thể là một ảnh poster bạn tạo riêng
                // hoặc bạn có thể để null và dùng ảnh đại diện mặc định ở frontend.
                // Ví dụ: $this->thumbnailUrl = Storage::url('path/to/video_poster.jpg');
                $this->thumbnailUrl = null; // Tạm thời để null
            }
        }
    }

    /**
     * Get the channels the event should broadcast on.
     */
    public function broadcastOn(): array
    {
        return [
            new Channel('stories'),
        ];
    }
}