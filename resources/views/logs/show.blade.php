<!-- resources/views/logs/show.blade.php -->

@extends('layouts.tabler')

@section('content')
    <div class="container">
        <h2>Log của thiết bị: {{ $deviceId }} (IP: {{ $ip }})</h2>
        <div class="log-content">
            <pre>{{ $logContent }}</pre>
        </div>
        <a href="{{ route('logs.devices', ['ip' => $ip]) }}">Quay lại danh sách thiết bị</a>
    </div>
@endsection
