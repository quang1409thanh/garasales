<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Session List</title>
</head>
<body>
<h1>All Sessions</h1>
<table border="1">
    <thead>
    <tr>
        <th>Session ID</th>
        <th>Data</th>
    </tr>
    </thead>
    <tbody>
    @foreach($sessions as $sessionId => $data)
        <tr>
            <td>{{ $sessionId }}</td>
            <td>
                <pre>{{ print_r($data, true) }}</pre>
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
</body>
</html>
