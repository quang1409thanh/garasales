<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Log;

class LogCsrfErrors
{
    public function handle($request, Closure $next)
    {
        // Kiểm tra nếu là yêu cầu POST và token không hợp lệ
        if ($request->isMethod('post') && !$request->session()->token() == $request->input('_token')) {
            // Ghi log thông tin lỗi 419
            Log::error('CSRF token mismatch', [
                'ip' => $request->ip(),
                'url' => $request->fullUrl(),
                'data' => $request->all(), // Lưu tất cả dữ liệu POST
                'session_id' => $request->session()->getId(),
                'timestamp' => now()->toDateTimeString(),
            ]);

            return response()->json(['message' => 'CSRF token mismatch'], 419);
        }

        // Ghi log thông tin yêu cầu
        Log::info('Incoming request', [
            'ip' => $request->ip(),
            'url' => $request->fullUrl(),
            'method' => $request->method(),
            'data' => $request->all(),
            'timestamp' => now()->toDateTimeString(),
        ]);

        return $next($request);
    }
}
