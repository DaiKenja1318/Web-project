<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Story; // Import model Story

class DashboardController extends Controller
{
    /**
     * Hiển thị trang dashboard của admin cùng với danh sách các story.
     */
    public function index()
    {
        // Lấy TẤT CẢ các story, sắp xếp mới nhất lên đầu,
        // kèm theo thông tin của người đăng bài để tránh query N+1.
        $stories = Story::with('user')->latest()->get();

        // Trả về view admin.dashboard và truyền biến 'stories' sang
        return view('admin.dashboard', compact('stories'));
    }

    public function editStory(Story $story)
    {
        return view('admin.stories.edit', compact('story'));
    }

    public function updateStory(Request $request, Story $story)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        $story->update($validated);

        return redirect()->route('admin.dashboard')->with('success', 'Story has been updated successfully!');
    }

    public function destroyStory(Story $story)
    {
        // Thực hiện xóa story
        $story->delete();

        // Chuyển hướng trở lại trang dashboard của admin với một thông báo thành công
        return redirect()->route('admin.dashboard')->with('success', 'Story has been deleted successfully!');
    }
}