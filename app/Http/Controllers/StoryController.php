<?php

namespace App\Http\Controllers;

// THÊM CÁC DÒNG USE NÀY VÀO ĐÂY
use App\Models\Story; // <-- Dòng quan trọng nhất cần thêm
use App\Events\StoryCreated;
use Illuminate\Http\Request;
use Spatie\Image\Image;
use Spatie\Image\Enums\Fit;
use Illuminate\Support\Facades\Storage;

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
            $imagePath = $file->store('stories', 's3');
            $tempPath = $file->getRealPath();
        
            // Lấy định dạng từ file gốc (ví dụ 'jpg', 'png')
            $format = $file->getClientOriginalExtension();
        
            // Tải ảnh tạm thời lên S3 với định dạng được chỉ định rõ ràng
            $imageLarge = Image::load($tempPath)->width(1200)->quality(85)->optimize();
            Storage::disk('s3')->put($imagePath, (string) $imageLarge->save(null, 85, $format));
        
            $pathInfo = pathinfo($imagePath);
            $directory = $pathInfo['dirname'];
            $filename = $pathInfo['basename'];
            
            $pathMedium = $directory . '/medium_' . $filename;
            $imageMedium = Image::load($tempPath)->width(800)->quality(85)->optimize();
            Storage::disk('s3')->put($pathMedium, (string) $imageMedium->save(null, 85, $format));
        
            $pathThumb = $directory . '/thumb_' . $filename;
            $imageThumb = Image::load($tempPath)->width(400)->quality(85)->optimize();
            Storage::disk('s3')->put($pathThumb, (string) $imageThumb->save(null, 85, $format));
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