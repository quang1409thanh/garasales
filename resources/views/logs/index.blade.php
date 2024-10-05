<!DOCTYPE html>
<html>
<head>
    <title>Danh sách Log</title>
</head>
<body>
<h1>Danh sách Log theo IP</h1>
<ul>
    @foreach ($ips as $ip)
        <li>
            <a href="{{ route('logs.show', $ip) }}">{{ $ip }}</a>
        </li>
    @endforeach
</ul>
</body>
</html>
