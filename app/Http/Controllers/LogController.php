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
    /**
     * (Optional) Trả về toàn bộ log laravel.log (chung).
     */
    public function getLogs(Request $request)
    {
        $logFilePath = storage_path('logs/laravel.log');

        if (!file_exists($logFilePath)) {
            return response()->json(['message' => 'File log không tồn tại.'], 404);
        }

        return response()->download($logFilePath);
    }

    public function listIPs()
    {
        $ipDirectories = File::directories(storage_path('logs/requests'));
        $ips = array_map('basename', $ipDirectories);

        return view('logs.ips', compact('ips'));
    }


    /**
     * Hiển thị danh sách các thiết bị theo IP.
     */
    public function listDevices($ip)
    {
        $deviceFiles = File::files(storage_path("logs/requests/{$ip}"));

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
        $logFilePath = storage_path("logs/requests/{$ip}/{$deviceId}.log");

        if (File::exists($logFilePath)) {
            $logContent = File::get($logFilePath);
        } else {
            $logContent = "Log không tồn tại.";
        }

        return view('logs.show', compact('logContent', 'ip', 'deviceId'));
    }

    /**
     * Hiển thị log của thiết bị hiện tại dựa trên IP và device ID của người dùng.
     */
    public function showILog(Request $request)
    {
        // Lấy IP thật
        $ipHeader = $request->header('X-Forwarded-For');
        $rawIp = $ipHeader ? trim(explode(',', $ipHeader)[0]) : $request->ip();

        // Làm sạch IP và device_id để truy cập file
        $cleanIp = str_replace([':', '.'], '-', $rawIp);
        $deviceId = Cookie::get('device_id');
        $cleanDeviceId = preg_replace('/[^a-zA-Z0-9\-]/', '', $deviceId);

        $logFilePath = storage_path("logs/requests/{$cleanIp}/{$cleanDeviceId}.log");

        if (!File::exists($logFilePath)) {
            return view('logs.show', ['error' => 'Log file không tồn tại.']);
        }

        $logContent = File::get($logFilePath);

        return view('logs.ishow', [
            'ip' => $cleanIp,
            'deviceId' => $cleanDeviceId,
            'logContent' => $logContent,
        ]);
    }
}
