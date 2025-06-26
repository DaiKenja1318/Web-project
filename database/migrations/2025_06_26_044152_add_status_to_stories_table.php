<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('stories', function (Blueprint $table) {
            // Thêm cột 'status' vào bảng 'stories' đã tồn tại
            $table->string('status')->default('draft')->after('image');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('stories', function (Blueprint $table) {
            // Định nghĩa cách để xóa cột này nếu cần rollback
            $table->dropColumn('status');
        });
    }
};