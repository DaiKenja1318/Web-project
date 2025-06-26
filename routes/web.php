<?php

use Illuminate\Support\Facades\Route;

// Import các Controller
use App\Http\Controllers\StoryController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\StoryController as AdminStoryController; // Controller mới cho Admin

/*
|--------------------------------------------------------------------------
| Public Routes (Dành cho tất cả mọi người)
|--------------------------------------------------------------------------
*/

// Trang chủ hiển thị danh sách các story
Route::get('/', [StoryController::class, 'index'])->name('home');

// Xem chi tiết một story
// Đặt dưới các route 'stories/*' khác để tránh xung đột
Route::get('/stories/{story}', [StoryController::class, 'show'])->name('stories.show');

/*
|--------------------------------------------------------------------------
| Authenticated User Routes (Dành cho người dùng đã đăng nhập)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {

    // === Story Routes cho người dùng thường ===
    Route::get('/stories/create', [StoryController::class, 'create'])->name('stories.create');
    Route::post('/stories', [StoryController::class, 'store'])->name('stories.store');
    // Nếu người dùng có thể tự sửa/xóa story của mình, thêm 2 dòng này
    // Route::get('/stories/{story}/edit', [StoryController::class, 'edit'])->name('stories.edit');
    // Route::put('/stories/{story}', [StoryController::class, 'update'])->name('stories.update');
    // Route::delete('/stories/{story}', [StoryController::class, 'destroy'])->name('stories.destroy');

    // === Comment Route ===
    Route::post('/stories/{story}/comments', [CommentController::class, 'store'])->name('comments.store');

    // === Profile Routes ===
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


/*
|--------------------------------------------------------------------------
| Admin Routes (Chỉ dành cho Admin)
|--------------------------------------------------------------------------
*/
// Sử dụng prefix 'admin' cho URL và 'admin.' cho tên route
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    
    // Admin Dashboard
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    // Quản lý Stories cho Admin (Sử dụng Route::resource cho gọn)
    // Dòng này sẽ tự động tạo ra các route:
    // - admin.stories.index (GET admin/stories)
    // - admin.stories.create (GET admin/stories/create)
    // - admin.stories.store (POST admin/stories)
    // - admin.stories.show (GET admin/stories/{story})
    // - admin.stories.edit (GET admin/stories/{story}/edit)
    // - admin.stories.update (PUT/PATCH admin/stories/{story})
    // - admin.stories.destroy (DELETE admin/stories/{story})
    Route::resource('stories', AdminStoryController::class);

});


// Auth routes
require __DIR__.'/auth.php';