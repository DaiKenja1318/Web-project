<?php

namespace App\Http\Controllers;

use App\Models\Story;
use App\Models\Comment; // Đảm bảo đã import Model Comment
use App\Events\CommentCreated; // Import Event cho comment
use Illuminate\Http\Request;

class CommentController extends Controller
{

    /**
     * Lưu một comment mới vào database cho một story cụ thể.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Story  $story
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request, Story $story)
    {
        // 1. Validate dữ liệu gửi lên, đảm bảo content không được để trống.
        $validated = $request->validate([
            'content' => 'required|string|max:2000',
        ]);

        // 2. Tạo comment mới thông qua relationship từ Story.
        // Laravel sẽ tự động điền 'story_id'.
        $comment = $story->comments()->create([
            'user_id' => auth()->id(), // Lấy ID của user đang đăng nhập
            'content' => $validated['content'],
        ]);

        // 3. (Quan trọng) Gửi event tới Reverb để cập nhật real-time.
        // Chúng ta load 'user' để gửi kèm thông tin người bình luận.
        broadcast(new CommentCreated($comment->load('user')))->toOthers();

        // 4. Chuyển hướng người dùng quay trở lại trang vừa rồi với một thông báo thành công.
        return back()->with('success', 'Comment posted successfully!');
    }
}