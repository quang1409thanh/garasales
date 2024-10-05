<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;

class LogController extends Controller
{
    public function getLogs()
    {
        // Đường dẫn tới file log hiện tại
        $filePath = storage_path('logs/laravel.log');

        // Kiểm tra xem file log có tồn tại không
        if (!File::exists($filePath)) {
            return response()->json(['message' => 'Log file not found.'], 404);
        }

        // Lấy nội dung của file log
        $logs = File::get($filePath);

        // Trả về file log dưới dạng response
        return response($logs)
            ->header('Content-Type', 'text/plain')
            ->header('Content-Disposition', 'attachment; filename="laravel.log"');
    }
}
