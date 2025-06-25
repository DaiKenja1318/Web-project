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
            'image' => 'nullable|image|max:2048', // Cho phép ảnh, tối đa 2MB
        ]);

        $imagePath = null; // Mặc định là không có ảnh

        // 2. Kiểm tra và upload file ảnh nếu người dùng có chọn
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('stories', 'public');

        }

        // 3. Tạo story mới trong database
        // Với $fillable đã đúng trong Model, Laravel sẽ lưu tất cả các trường
        Story::create([
            'title' => $validated['title'],
            'content' => $validated['content'],
            'image' => $imagePath, // Giá trị này sẽ là null hoặc đường dẫn file
            'user_id' => auth()->id(), // Giả sử bạn muốn lưu cả ID của người tạo
        ]);

        // 4. Phát sóng sự kiện real-time (nếu có)
        broadcast(new StoryCreated($story))->toOthers();

        // 5. Chuyển hướng người dùng về trang chi tiết story vừa tạo
        return redirect()->route('stories.show', $story)
                         ->with('success', 'Story created successfully!');
    }

    /**
     * Hiển thị chi tiết một story và các comment của nó.
     */
    public function show(Story $story)
    {
        // Tải sẵn các mối quan hệ để tránh lỗi N+1
        $story->load(['user', 'comments.user']);
        return view('stories.show', compact('story'));
    }
}