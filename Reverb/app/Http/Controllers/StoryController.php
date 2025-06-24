<?php

namespace App\Http\Controllers;

// THÊM CÁC DÒNG USE NÀY VÀO ĐÂY
use App\Models\Story; // <-- Dòng quan trọng nhất cần thêm
use App\Events\StoryCreated;
use Illuminate\Http\Request;

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
        // ... code validate và lưu story ...
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $imagePath = $request->file('image')->store('stories', 'public');

        $story = $request->user()->stories()->create([
            'title' => $validated['title'],
            'content' => $validated['content'],
            'image' => $imagePath,
        ]);

        $story->load('user');

        // Bây giờ Laravel đã biết StoryCreated là gì
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