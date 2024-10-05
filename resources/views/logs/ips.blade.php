<!-- resources/views/logs/ips.blade.php -->

@extends('layouts.tabler')

@section('content')
    <div class="container">
        <h2>Danh sách các IP</h2>
        <ul>
            @forelse($ips as $ip)
                <li>
                    <!-- Liên kết đến trang hiển thị danh sách thiết bị theo IP -->
                    <a href="{{ route('logs.devices', ['ip' => $ip]) }}">{{ $ip }}</a>
                </li>
            @empty
                <li>Không có IP nào được ghi log.</li>
            @endforelse
        </ul>
    </div>
@endsection
