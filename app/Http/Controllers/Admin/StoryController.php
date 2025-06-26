<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Story;
use Illuminate\Http\Request;

class StoryController extends Controller
{
    /**
     * Hiển thị danh sách tất cả các story cho admin quản lý.
     * Đây là trang thay thế cho việc hiển thị list story trên dashboard.
     * Route: admin.stories.index (GET admin/stories)
     */
    public function index()
    {
        // Lấy TẤT CẢ các story, sắp xếp mới nhất lên đầu,
        // kèm theo thông tin của người đăng bài để tránh query N+1.
        $stories = Story::with('user')->latest()->paginate(15); // Dùng paginate cho danh sách dài

        // Trả về một view riêng để quản lý story, không phải dashboard
        return view('admin.stories.index', compact('stories'));
    }

    /**
     * Hiển thị form để tạo một story mới.
     * Route: admin.stories.create (GET admin/stories/create)
     */
    public function create()
    {
        // Cần truyền một đối tượng Story rỗng để form có thể tái sử dụng
        return view('admin.stories.edit', [
            'story' => new Story()
        ]);
    }

    /**
     * Lưu một story mới vào database.
     * Route: admin.stories.store (POST admin/stories)
     */
    public function store(Request $request)
    {
        // (Bạn có thể thêm logic validate và tạo mới ở đây sau)
        // ...
    }


    /**
     * Hiển thị một story cụ thể (Admin có thể không cần chức năng này).
     * Route: admin.stories.show (GET admin/stories/{story})
     */
    public function show(Story $story)
    {
        // Chuyển hướng đến trang xem chi tiết công khai
        return redirect()->route('stories.show', $story);
    }


    /**
     * Hiển thị form để chỉnh sửa một story đã có.
     * Đổi tên từ 'editStory' thành 'edit'.
     * Route: admin.stories.edit (GET admin/stories/{story}/edit)
     */
    public function edit(Story $story)
    {
        return view('admin.stories.edit', compact('story'));
    }

    /**
     * Cập nhật thông tin của một story trong database.
     * Đổi tên từ 'updateStory' thành 'update'.
     * Route: admin.stories.update (PUT/PATCH admin/stories/{story})
     */
    public function update(Request $request, Story $story)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'status' => 'required|in:draft,published', // Thêm validation cho status
            'image' => 'nullable|image|max:2048' // Thêm validation cho ảnh
        ]);
        
        // (Thêm logic xử lý upload ảnh nếu có)

        $story->update($validated);

        // Chuyển hướng về trang danh sách stories của admin
        return redirect()->route('admin.stories.index')->with('success', 'Story has been updated successfully!');
    }

    /**
     * Xóa một story khỏi database.
     * Đổi tên từ 'destroyStory' thành 'destroy'.
     * Route: admin.stories.destroy (DELETE admin/stories/{story})
     */
    public function destroy(Story $story)
    {
        // (Thêm logic xóa file ảnh nếu có)

        $story->delete();

        // Chuyển hướng trở lại trang danh sách stories của admin
        return redirect()->route('admin.stories.index')->with('success', 'Story has been deleted successfully!');
    }
}