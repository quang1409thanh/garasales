<!DOCTYPE html>
<html>
<head>
    <title>Upload Image</title>
</head>
<body>
@if ($message = Session::get('success'))
    <div>{{ $message }}</div>
@endif

@if ($errors->any())
    <div>
        <strong>Whoops!</strong> There were some problems with your input.
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form action="{{ route('upload.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <input type="file" name="image" required>
    <button type="submit">Upload</button>
</form>
</body>
</html>
