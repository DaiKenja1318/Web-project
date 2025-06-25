<?php

namespace App\Http\Controllers;

// Các use statement cần thiết
use App\Models\Story;
use App\Events\StoryCreated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

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
     * === HÀM STORE ĐÃ ĐƯỢC SỬA LẠI HOÀN TOÀN ===
     * Logic được đơn giản hóa, chỉ upload file gốc.
     */
    public function store(Request $request)
    {
        // 1. Validate dữ liệu
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'image' => 'nullable|image|max:2048', // Bỏ mimes để đơn giản hóa
        ]);

        $imagePath = null;

        // 2. Upload file gốc lên S3 (nếu có)
        if ($request->hasFile('image')) {
            // Laravel sẽ tự động tạo tên file ngẫu nhiên và lưu vào thư mục 'stories' trên S3
            // Nó trả về đường dẫn, ví dụ: "stories/aBcXyZ123.jpg"
            $imagePath = $request->file('image')->store('stories', 's3');
        }

        // 3. Tạo story trong database
        $story = auth()->user()->stories()->create([
            'title' => $validated['title'],
            'content' => $validated['content'],
            'image' => $imagePath, // Lưu đường dẫn trả về từ S3
        ]);

        // 4. Phát sóng sự kiện
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