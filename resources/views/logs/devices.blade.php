<!-- resources/views/logs/devices.blade.php -->

@extends('layouts.tabler')

@section('content')
    <div class="container">
        <h2>Danh sách thiết bị cho IP: {{ $ip }}</h2>
        <ul>
            @forelse($devices as $device)
                <li>
                    <!-- Liên kết đến trang hiển thị log của thiết bị theo IP và device -->
                    <a href="{{ route('logs.show', ['ip' => $ip, 'device' => $device]) }}">{{ $device }}</a>
                </li>
            @empty
                <li>Không có thiết bị nào được ghi log cho IP này.</li>
            @endforelse
        </ul>
        <a href="{{ route('logs.ips') }}">Quay lại danh sách IP</a>
    </div>
@endsection
