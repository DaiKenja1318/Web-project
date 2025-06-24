<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Kiểm tra xem người dùng đã đăng nhập VÀ có phải là admin không
        if (auth()->check() && auth()->user()->isAdmin()) {
            // Nếu đúng, cho phép đi tiếp
            return $next($request);
        }

        // Nếu không, từ chối truy cập với lỗi 403 (Forbidden)
        abort(403, 'ACCESS DENIED. YOU ARE NOT AN ADMIN.');
    }
}