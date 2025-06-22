<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StoryController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\ProfileController;

// Route chính, không cần auth
Route::get('/', [StoryController::class, 'index'])->name('home');

// Route để xem chi tiết story, không cần auth
Route::get('/stories/{story}', [StoryController::class, 'show'])->name('stories.show');

// Nhóm các route cần đăng nhập
Route::middleware('auth')->group(function () {
    // Route để hiển thị form tạo story
    Route::get('/stories/create', [StoryController::class, 'create'])->name('stories.create');
    // Route để lưu story mới
    Route::post('/stories', [StoryController::class, 'store'])->name('stories.store');

    // Route để đăng comment
    Route::post('/stories/{story}/comments', [CommentController::class, 'store'])->name('comments.store');

    // Profile routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


// Các route xác thực mặc định của Breeze
Route::get('/dashboard', function () {
    return redirect()->route('home');
})->middleware(['auth', 'verified'])->name('dashboard');

require __DIR__.'/auth.php';

// Nhóm các route chỉ dành cho Admin
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', function() {
        return view('admin.dashboard');
    })->name('dashboard');
});