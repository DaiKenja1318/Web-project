<?php

namespace App\Http\Controllers;

use App\Models\Story;
use App\Events\StoryCreated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class StoryController extends Controller
{
    public function index()
    {
        $stories = Story::with('user')->latest()->paginate(10);
        return view('stories.index', ['stories' => $stories]);
    }

    public function create()
    {
        return view('stories.create');
    }

    // === THAY THẾ TOÀN BỘ HÀM STORE BẰNG PHIÊN BẢN ĐƠN GIẢN NÀY ===
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'image' => 'nullable|image|max:2048',
        ]);

        $imagePath = null;

        if ($request->hasFile('image')) {
            // Để Laravel tự động upload file gốc lên S3 và trả về đường dẫn
            $imagePath = $request->file('image')->store('stories', 's3');
        }

        $story = auth()->user()->stories()->create([
            'title' => $validated['title'],
            'content' => $validated['content'],
            'image' => $imagePath, // Lưu đường dẫn này vào database
        ]);

        broadcast(new StoryCreated($story))->toOthers();

        return redirect()->route('stories.show', $story)
                         ->with('success', 'Story created successfully!');
    }

    public function show(Story $story)
    {
        $story->load(['user', 'comments.user']);
        return view('stories.show', compact('story'));
    }
}