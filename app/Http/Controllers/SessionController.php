<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use File;

class SessionController extends Controller
{
    public function getAllSessions()
    {
        // Đường dẫn tới thư mục lưu session
        $sessionPath = storage_path('framework/sessions');

        // Lấy danh sách các file trong thư mục sessions
        $sessionFiles = File::files($sessionPath);

        // Tạo mảng để lưu trữ dữ liệu session
        $sessions = [];

        // Duyệt qua từng file session và đọc nội dung
        foreach ($sessionFiles as $file) {
            $sessionData = File::get($file);

            // Chuyển dữ liệu session từ dạng serialize sang dạng mảng
            $sessions[$file->getFilename()] = unserialize($sessionData);
        }

        // Trả về dưới dạng JSON hoặc render ra HTML nếu cần
        return view('sessions.index', compact('sessions'));
    }
}
