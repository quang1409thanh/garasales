@extends('layouts.tabler')

@section('content')
    <div class="container">
        <h2>Danh sách session cho Thiết bị: {{ $device }} (IP: {{ $ip }})</h2>
        <ul>
            @foreach($sessions as $session)
                <li><a href="{{ route('logs.show', ['ip' => $ip, 'device' => $device, 'session' => $session]) }}">{{ $session }}</a></li>
            @endforeach
        </ul>
        <a href="{{ route('logs.devices', $ip) }}" class="btn btn-primary">Back</a> <!-- Nút Back -->
    </div>
@endsection
