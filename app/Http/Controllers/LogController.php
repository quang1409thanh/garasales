<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Cookie;

class LogController extends Controller
{
    /**
     * Hiển thị danh sách các IP.
     */
    public function getLogs(Request $request)
    {
        // Đường dẫn đến file log, bạn có thể điều chỉnh theo nhu cầu
        $logFilePath = storage_path('logs/laravel.log');

        // Kiểm tra xem file log có tồn tại không
        if (!file_exists($logFilePath)) {
            return response()->json(['message' => 'File log không tồn tại.'], 404);
        }

        // Nếu bạn muốn trả về nội dung file log
        $logs = file_get_contents($logFilePath);

        // Hoặc nếu bạn muốn tải file log
        return response()->download($logFilePath);

        // Nếu muốn trả về nội dung dưới dạng JSON
        // return response()->json(['logs' => $logs]);
    }

    public function listIPs()
    {
        // Lấy danh sách các thư mục IP trong thư mục logs/requests
        $ipDirectories = File::directories(storage_path('logs/requests'));

        // Chuyển thành danh sách IP (lấy tên thư mục từ đường dẫn)
        $ips = array_map('basename', $ipDirectories);

        return view('logs.ips', compact('ips'));
    }

    /**
     * Hiển thị danh sách các thiết bị theo IP.
     */
    public function listDevices($ip)
    {
        // Lấy danh sách các file log theo IP (mỗi file log tương ứng với một device)
        $deviceFiles = File::files(storage_path("logs/requests/{$ip}"));

        // Chuyển thành danh sách các thiết bị (lấy tên file không có phần mở rộng .log)
        $devices = array_map(function ($file) {
            return pathinfo($file, PATHINFO_FILENAME);
        }, $deviceFiles);

        return view('logs.devices', compact('devices', 'ip'));
    }

    /**
     * Hiển thị log theo IP và thiết bị.
     */
    public function showLog($ip, $deviceId)
    {
        // Đường dẫn đến file log của thiết bị
        $logFilePath = storage_path("logs/requests/{$ip}/{$deviceId}.log");

        // Kiểm tra nếu file log tồn tại
        if (File::exists($logFilePath)) {
            $logContent = File::get($logFilePath);
        } else {
            $logContent = "Log không tồn tại.";
        }

        // Trả về view để hiển thị log
        return view('logs.show', compact('logContent', 'ip', 'deviceId'));
    }

    /**
     * Hiển thị log của thiết bị hiện tại dựa trên IP và device ID của người dùng.
     */
    public function showILog()
    {
        // Lấy IP từ request hiện tại
        $ip = request()->ip();

        // Lấy device ID từ cookie
        $deviceId = Cookie::get('device_id');

        // Đường dẫn đến file log của IP và deviceId
        $logFilePath = storage_path("logs/requests/{$ip}/{$deviceId}.log");

        // Kiểm tra nếu file log tồn tại
        if (!File::exists($logFilePath)) {
            return view('logs.show', ['error' => 'Log file không tồn tại.']);
        }

        // Đọc nội dung file log
        $logContent = File::get($logFilePath);

        // Trả về view để hiển thị log
        return view('logs.ishow', [
            'ip' => $ip,
            'deviceId' => $deviceId,
            'logContent' => $logContent,
        ]);
    }
}
