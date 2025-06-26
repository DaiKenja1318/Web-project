<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
// Không cần Request, không cần Story nữa

class DashboardController extends Controller
{
    /**
     * Hiển thị trang dashboard của admin.
     * Nhiệm vụ hiển thị danh sách story đã được chuyển sang StoryController@index.
     */
    public function index()
    {
        // Bây giờ, Dashboard chỉ đơn giản là hiển thị một view.
        // Việc quản lý stories sẽ có một trang riêng (admin/stories).
        return view('admin.dashboard');
    }

    // XÓA TOÀN BỘ CÁC PHƯƠNG THỨC editStory, updateStory, destroyStory KHỎI ĐÂY.
}