<?php

namespace App\Http\Controllers;

// Các use statement cần thiết
use App\Models\Story;
use App\Events\StoryCreated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage; // Quan trọng cho việc lấy URL

class StoryController extends Controller
{
    /**
     * Hiển thị danh sách các story (trang chủ).
     */
    public function index()
    {
        $stories = Story::with('user')->latest()->paginate(10);
        return view('stories.index', ['stories' => $stories]);
    }

    /**
     * Hiển thị form để tạo story mới.
     */
    public function create()
    {
        return view('stories.create');
    }

    /**
     * === HÀM STORE ĐÃ ĐƯỢC SỬA LẠI ĐỂ ĐẢM BẢO HOẠT ĐỘNG ===
     * Logic được đơn giản hóa tối đa, chỉ dùng hàm store() của Laravel.
     */
    public function store(Request $request)
    {
        // 1. Validate dữ liệu đầu vào
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            // --- THAY ĐỔI QUAN TRỌNG Ở ĐÂY ---
            // 'file' thay vì 'image' để chung chung hơn
            // mimes: định dạng file cho phép (thêm các định dạng video phổ biến)
            // max: tăng giới hạn kích thước file cho video (ví dụ 50MB)
            'file' => 'nullable|file|mimes:jpg,jpeg,png,gif,mp4,mov,ogg,qt|max:51200', // 51200 KB = 50 MB
        ]);

        $filePath = null; // Đường dẫn file
        $fileType = 'none'; // Loại file: image, video, hoặc none

        // 2. Kiểm tra và xử lý file upload
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            
            // Lấy loại MIME của file để xác định là ảnh hay video
            $mimeType = $file->getMimeType();

            if (str_starts_with($mimeType, 'image/')) {
                $fileType = 'image';
                $filePath = $file->store('stories/images', 'public'); // Lưu ảnh vào thư mục riêng
            } elseif (str_starts_with($mimeType, 'video/')) {
                $fileType = 'video';
                $filePath = $file->store('stories/videos', 'public'); // Lưu video vào thư mục riêng
            }
        }

        // 3. Tạo story mới trong database
        $story = auth()->user()->stories()->create([
            'title' => $validated['title'],
            'content' => $validated['content'],
            'image' => $filePath, // Ta vẫn dùng cột 'image' để lưu đường dẫn chung
            'file_type' => $fileType, // Thêm một cột mới để biết đây là loại file gì
        ]);

        // 4. Phát sóng sự kiện real-time
        broadcast(new StoryCreated($story))->toOthers();

        // 5. Chuyển hướng
        return redirect()->route('stories.show', $story)
                         ->with('success', 'Story created successfully!');
    }

    /**
     * Hiển thị chi tiết một story và các comment của nó.
     */
    public function show(Story $story)
    {
        $story->load(['user', 'comments.user']);
        return view('stories.show', compact('story'));
    }
}