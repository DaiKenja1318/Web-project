<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Story;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class StoryController extends Controller
{
    /**
     * Hiển thị danh sách tất cả các story cho admin quản lý.
     */
    public function index()
    {
        $stories = Story::with('user')->latest()->paginate(15);
        return view('admin.stories.index', compact('stories'));
    }

    /**
     * Hiển thị form để tạo một story mới.
     */
    public function create()
    {
        return view('admin.stories.edit', ['story' => new Story()]);
    }

    /**
     * Lưu một story mới vào database.
     */
    public function store(Request $request)
    {
        // (Đây là phần logic cơ bản, bạn có thể mở rộng sau)
         $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'status' => 'required|in:draft,published',
            'image' => 'nullable|image|max:2048'
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('stories', 'public');
        }

        auth()->user()->stories()->create($validated);

        return redirect()->route('admin.stories.index')->with('success', 'Story created successfully!');
    }


    /**
     * Hiển thị form để chỉnh sửa một story đã có.
     */
    public function edit(Story $story)
    {
        return view('admin.stories.edit', compact('story'));
    }

    /**
     * Cập nhật thông tin của một story trong database.
     */
    public function update(Request $request, Story $story)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'status' => 'required|in:draft,published',
            'image' => 'nullable|image|max:2048'
        ]);
        
        if ($request->hasFile('image')) {
            // Xóa ảnh cũ nếu có
            if ($story->image) {
                Storage::disk('public')->delete($story->image);
            }
            $validated['image'] = $request->file('image')->store('stories', 'public');
        }

        $story->update($validated);

        return redirect()->route('admin.stories.index')->with('success', 'Story updated successfully!');
    }

    /**
     * Xóa một story khỏi database.
     */
    public function destroy(Story $story)
    {
        if ($story->image) {
            Storage::disk('public')->delete($story->image);
        }

        $story->delete();

        return redirect()->route('admin.stories.index')->with('success', 'Story deleted successfully!');
    }
}