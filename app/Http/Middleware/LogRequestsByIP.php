<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cookie;

class LogRequestsByIP
{
    public function handle($request, Closure $next)
    {
        // Lấy IP của yêu cầu
        $ip = $request->header('X-Forwarded-For') ?? $request->ip();

        // Kiểm tra và gán một cookie device_id cho mỗi thiết bị
        if (!Cookie::has('device_id')) {
            $deviceId = Str::uuid(); // Tạo UUID mới
            Cookie::queue('device_id', $deviceId, 60 * 24 * 365);
        } else {
            $deviceId = Cookie::get('device_id');
        }
        // Làm sạch IP để tránh lỗi khi đặt tên file
        $cleanIp = str_replace([':', '.'], '-', $ip);

        // Làm sạch device_id
        $cleanDeviceId = preg_replace('/[^a-zA-Z0-9\-]/', '', $deviceId);

        // Tạo path log rõ ràng, dễ debug
        $logFilePath = storage_path("logs/requests/{$cleanIp}/{$cleanDeviceId}.log");
        // Tạo thư mục nếu chưa có
        if (!File::exists(dirname($logFilePath))) {
            File::makeDirectory(dirname($logFilePath), 0755, true, true);
        }

        // Chuẩn bị để log request và response trên cùng 1 dòng
        $requestLog = sprintf(
            "[%s] [IP: %s] [Device: %s] [REQUEST] %s %s",
            now()->toDateTimeString(),
            $ip,
            $deviceId,
            $request->method(),
            $request->fullUrl()
        );

        try {
            // Xử lý request và lấy response
            $response = $next($request);

            // Thêm thông tin response vào log
            $responseLog = sprintf(" [RESPONSE] Status: %d", $response->status());

            // Ghi log request và response trên cùng một dòng
            File::append($logFilePath, $requestLog . $responseLog . "\n");

            return $response;
        } catch (\Exception $e) {
            // Thêm thông tin lỗi vào log
            $errorLog = sprintf(" [ERROR] %s: %s", get_class($e), $e->getMessage());

            // Ghi log request và lỗi
            File::append($logFilePath, $requestLog . $errorLog . "\n");

            throw $e; // Tiếp tục ném lỗi để xử lý sau
        }
    }
}
