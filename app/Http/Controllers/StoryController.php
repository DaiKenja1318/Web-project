<?php

namespace App\Http\Controllers;

use App\Models\Story;
use App\Events\StoryCreated;
use App\Events\StoryUpdatedEvent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Gate;

class StoryController extends Controller
{
    /**
     * Hiển thị danh sách các story.
     */
    public function index()
    {
        // Chỉ hiển thị các story đã 'published' cho người dùng thông thường
        $stories = Story::where('status', 'published')->with('user')->latest()->paginate(10);
        return view('stories.index', ['stories' => $stories]);
    }

    /**
     * Hiển thị form để tạo story mới.
     */
    public function create()
    {
        // Truyền một đối tượng Story rỗng để tái sử dụng form
        return view('stories.edit', ['story' => new Story()]);
    }

    /**
     * Lưu một story mới vào database.
     */
    public function store(Request $request)
    {
        // Validate đầy đủ các trường từ form
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'image' => 'nullable|image|max:2048',
            'status' => 'required|in:draft,published', // Thêm validation cho status
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('stories', 'public');
        }

        // Tạo story mới với đầy đủ thông tin
        $story = auth()->user()->stories()->create([
            'title' => $validated['title'],
            'content' => $validated['content'],
            'image' => $imagePath,
            'status' => $validated['status'], // Lưu cả status
        ]);

        // Chỉ phát sóng sự kiện nếu story được publish
        if ($story->status == 'published') {
            broadcast(new StoryCreated($story))->toOthers();
        }

        return redirect()->route('stories.show', $story)
                         ->with('success', 'Story created successfully!');
    }

    /**
     * Hiển thị chi tiết một story.
     */
    public function show(Story $story)
    {
        // Ngăn chặn người khác xem story ở trạng thái 'draft'
        if ($story->status == 'draft' && auth()->id() !== $story->user_id) {
            abort(404);
        }

        $story->load(['user', 'comments.user']);
        return view('stories.show', compact('story'));
    }

    /**
     * Hiển thị form để chỉnh sửa một story đã có.
     */
    public function edit(Story $story)
    {
        // Kiểm tra quyền (chỉ chủ sở hữu mới được sửa)
        Gate::authorize('update', $story);

        return view('stories.edit', compact('story'));
    }

    /**
     * Cập nhật thông tin story trong database.
     */
    public function update(Request $request, Story $story)
    {
        // 1. Kiểm tra quyền của người dùng
        Gate::authorize('update', $story);

        // 2. Validate đầy đủ các trường
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'image' => 'nullable|image|max:2048',
            'status' => 'required|in:draft,published', // Thêm validation cho status
        ]);

        // 3. Xử lý upload ảnh mới (nếu có) và xóa ảnh cũ
        if ($request->hasFile('image')) {
            if ($story->image) {
                Storage::disk('public')->delete($story->image);
            }
            // Gán đường dẫn ảnh mới vào mảng validated để update
            $validated['image'] = $request->file('image')->store('stories', 'public');
        }

        // 4. Cập nhật story trong database
        // Laravel sẽ tự động cập nhật các trường có trong mảng $validated
        $story->update($validated);

        // 5. PHÁT SÓNG SỰ KIỆN UPDATE
        // Chỉ phát sóng nếu story được publish
        if ($story->status == 'published') {
             broadcast(new StoryUpdatedEvent($story))->toOthers();
        }

        // 6. Chuyển hướng người dùng về trang edit với thông báo thành công
        return redirect()->route('stories.edit', $story)
                         ->with('success', 'Story updated successfully!');
    }

    /**
     * Xóa một story.
     */
    public function destroy(Story $story)
    {
        Gate::authorize('delete', $story);

        // Xóa file ảnh liên quan nếu có
        if ($story->image) {
            Storage::disk('public')->delete($story->image);
        }

        $story->delete();
        
        // Bạn có thể phát sóng sự kiện xóa ở đây nếu cần
        // broadcast(new StoryDeleted($story->id))->toOthers();

        return redirect()->route('stories.index')
                         ->with('success', 'Story deleted successfully.');
    }
}