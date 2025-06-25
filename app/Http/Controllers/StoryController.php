<?php

namespace App\Http\Controllers;

// THÊM CÁC DÒNG USE NÀY VÀO ĐÂY
use App\Models\Story; // <-- Dòng quan trọng nhất cần thêm
use App\Events\StoryCreated;
use Illuminate\Http\Request;
use Spatie\Image\Image;
use Spatie\Image\Enums\Fit;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class StoryController extends Controller
{
    /**
     * Hiển thị danh sách các story (trang chủ)
     */
    public function index()
    {
        // Bây giờ Laravel mới biết 'Story' là App\Models\Story
        $stories = Story::with('user')->latest()->paginate(10);
        return view('stories.index', ['stories' => $stories]);
    }

    /**
     * Hiển thị form để tạo story mới
     */
    public function create()
    {
        return view('stories.create');
    }

    /**
     * Lưu story mới vào database
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        $imagePath = null;
if ($request->hasFile('image')) {
    $file = $request->file('image');
    
    // 1. Chỉ định thư mục và tạo một tên file ngẫu nhiên, duy nhất
    $directory = 'stories';
    $filename = Str::random(40) . '.' . $file->getClientOriginalExtension();
    
    // Tạo đường dẫn đầy đủ cho ảnh gốc
    $imagePath = $directory . '/' . $filename;

    // Lấy đường dẫn file tạm thời trên server để xử lý
    $tempPath = $file->getRealPath();

    // Lấy định dạng ảnh
    $format = $file->getClientOriginalExtension();

    // 2. Xử lý và upload các phiên bản
    
    // Upload ảnh gốc (phiên bản lớn)
    $imageLarge = Image::load($tempPath)->width(1200)->quality(85)->optimize();
    Storage::disk('s3')->put($imagePath, $imageLarge->encodeByExtension($format));

    // Tạo đường dẫn và upload phiên bản vừa (medium)
    $pathMedium = $directory . '/medium_' . $filename;
    $imageMedium = Image::load($tempPath)->width(800)->quality(85)->optimize();
    Storage::disk('s3')->put($pathMedium, $imageMedium->encodeByExtension($format));

    // Tạo đường dẫn và upload phiên bản nhỏ (thumbnail)
    $pathThumb = $directory . '/thumb_' . $filename;
    $imageThumb = Image::load($tempPath)->width(400)->quality(85)->optimize();
    Storage::disk('s3')->put($pathThumb, $imageThumb->encodeByExtension($format));
}

        $story = auth()->user()->stories()->create([
            'title' => $validated['title'],
            'content' => $validated['content'],
            'image' => $imagePath,
        ]);

        broadcast(new StoryCreated($story))->toOthers();

        return redirect()->route('stories.show', $story)
                         ->with('success', 'Story created successfully!');
    }

    /**
     * Hiển thị chi tiết một story và các comment của nó
     */
    public function show(Story $story)
    {
        $story->load(['user', 'comments.user']);
        return view('stories.show', compact('story'));
    }
    
}