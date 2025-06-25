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

    // 2. Kiểm tra và xử lý nếu có file ảnh được upload
    if ($request->hasFile('image')) {
        try {
            $file = $request->file('image');
            $extension = strtolower($file->getClientOriginalExtension());
            $tempPath = $file->getRealPath();

            // Chỉ cho phép các định dạng ảnh phổ biến
            $supportedFormats = ['jpg', 'jpeg', 'png', 'webp', 'gif'];
            if (!in_array($extension, $supportedFormats)) {
                // Nếu định dạng không được hỗ trợ, bỏ qua việc xử lý ảnh
                // hoặc bạn có thể trả về lỗi validation ở đây
                throw new \Exception('Invalid image format uploaded.');
            }
            
            // Tạo tên file ngẫu nhiên và thư mục lưu trên S3
            $directory = 'stories';
            $filename = Str::random(40) . '.' . $extension;
            $imagePath = $directory . '/' . $filename; // Đường dẫn ảnh gốc/lớn

            // --- Xử lý các phiên bản ảnh ---

            // Hàm trợ giúp để xử lý và upload một phiên bản
            $processAndUpload = function($width, $path) use ($tempPath, $extension) {
                $image = Image::load($tempPath)
                    ->width($width)
                    ->quality(85)
                    ->optimize();

                // Dùng match để gọi đúng hàm encode tường minh
                $encodedImage = match ($extension) {
                    'jpg', 'jpeg' => $image->toJpg(),
                    'png' => $image->toPng(),
                    'gif' => $image->toGif(),
                    'webp' => $image->toWebp(),
                };
                
                Storage::disk('s3')->put($path, (string) $encodedImage);
            };

            // Sử dụng hàm trợ giúp để tạo các phiên bản
            $processAndUpload(1200, $imagePath); // Phiên bản lớn
            $processAndUpload(800, $directory . '/medium_' . $filename); // Phiên bản vừa
            $processAndUpload(400, $directory . '/thumb_' . $filename); // Phiên bản nhỏ

        } catch (\Exception $e) {
            // Ghi lại lỗi chi tiết để gỡ rối mà không làm sập ứng dụng
            Log::error('Image processing failed: ' . $e->getMessage());
            // Đặt $imagePath về null để không lưu đường dẫn lỗi vào DB
            $imagePath = null;
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