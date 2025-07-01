<?php

use Illuminate\Support\Facades\Route;

// Import các Controller
use App\Http\Controllers\StoryController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\StoryController as AdminStoryController;

/*
|--------------------------------------------------------------------------
| Public Routes (Dành cho tất cả mọi người)
|--------------------------------------------------------------------------
*/

// Trang chủ hiển thị danh sách các story
Route::get('/', [StoryController::class, 'index'])->name('home');

// Xem chi tiết một story
Route::get('/stories/{story}', [StoryController::class, 'show'])->name('stories.show');
Route::get('/stories/create', [StoryController::class, 'create'])->name('stories.create');


/*
|--------------------------------------------------------------------------
| Authenticated User Routes (Dành cho người dùng đã đăng nhập)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::post('/stories', [StoryController::class, 'store'])->name('stories.store');

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

    // Quản lý Stories cho Admin (Sử dụng Route::resource cho gọn và đúng chuẩn)
    Route::resource('stories', AdminStoryController::class);
});


// Auth routes
require __DIR__.'/auth.php';