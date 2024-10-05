
<!-- resources/views/logs/show.blade.php -->

@extends('layouts.tabler')

@section('content')
    <div class="container">
        <h2>Log của thiết bị: {{ $deviceId }} (IP: {{ $ip }})</h2>
        @if(isset($error))
            <div class="alert alert-danger">{{ $error }}</div> <!-- Hiển thị thông báo lỗi nếu không tìm thấy log -->
        @else
            <h5>Nội dung log:</h5>
            <div class="log-content">
                <pre>{{ $logContent }}</pre>
            </div>
        @endif

        <a href="{{ route('logs.devices', ['ip' => $ip]) }}">Quay lại danh sách thiết bị</a>
    </div>
@endsection
