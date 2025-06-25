<?php

namespace App\Http\Controllers;

// Các use statement cần thiết, đã được sắp xếp lại
use App\Models\Story;
use App\Events\StoryCreated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Spatie\Image\Image;
use Spatie\Image\Enums\Fit;

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
     * Lưu story mới vào database.
     * PHIÊN BẢN ĐÃ SỬA LẠI HOÀN TOÀN ĐỂ ĐẢM BẢO HOẠT ĐỘNG.
     */
    public function store(Request $request)
{
    // 1. Validate dữ liệu đầu vào
    $validated = $request->validate([
        'title' => 'required|string|max:255',
        'content' => 'required|string',
        'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
    ]);

    $imagePath = null;
    $tempFilePath = null; // Khởi tạo biến file tạm

    // 2. Kiểm tra và xử lý nếu có file ảnh được upload
    if ($request->hasFile('image')) {
        $file = $request->file('image');
        $extension = $file->getClientOriginalExtension();

        // TẠO FILE TẠM VỚI PHẦN MỞ RỘNG ĐÚNG
        // Đây là bước sửa lỗi quan trọng nhất
        $tempFilePath = tempnam(sys_get_temp_dir(), 'img_') . '.' . $extension;
        
        // Tạo tên file ngẫu nhiên và thư mục lưu trên S3
        $directory = 'stories';
        $filename = Str::random(40) . '.' . $extension;
        $imagePath = $directory . '/' . $filename; // Đường dẫn ảnh gốc/lớn

        try {
            // --- Xử lý phiên bản LỚN (gốc) ---
            Image::load($file->getRealPath())
                ->width(1200)
                ->quality(85)
                ->optimize()
                ->save($tempFilePath); // Lưu vào file tạm có phần mở rộng
            // Upload file tạm lên S3
            Storage::disk('s3')->put($imagePath, fopen($tempFilePath, 'r+'));

            // --- Xử lý phiên bản VỪA (medium) ---
            $pathMedium = $directory . '/medium_' . $filename;
            Image::load($file->getRealPath())->width(800)->quality(85)->optimize()->save($tempFilePath);
            Storage::disk('s3')->put($pathMedium, fopen($tempFilePath, 'r+'));

            // --- Xử lý phiên bản NHỎ (thumb) ---
            $pathThumb = $directory . '/thumb_' . $filename;
            Image::load($file->getRealPath())->width(400)->quality(85)->optimize()->save($tempFilePath);
            Storage::disk('s3')->put($pathThumb, fopen($tempFilePath, 'r+'));

        } finally {
            // Đảm bảo file tạm luôn được xóa đi
            if ($tempFilePath && file_exists($tempFilePath)) {
                unlink($tempFilePath);
            }
        }
    }

    // 3. Tạo story trong database
    $story = auth()->user()->stories()->create([
        'title' => $validated['title'],
        'content' => $validated['content'],
        'image' => $imagePath,
    ]);

    // 4. Phát sóng sự kiện
    broadcast(new StoryCreated($story))->toOthers();

    // 5. Chuyển hướng người dùng
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