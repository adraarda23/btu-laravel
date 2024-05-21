<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>URL Shortener</title>
    <style>
        .container {
            width: 50%;
            margin: auto;
            text-align: center;
            margin-top: 50px;
        }
        .error {
            color: red;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>URL Shortener</h1>
        <form action="{{ route('shorten') }}" method="POST">
            @csrf
            <input type="text" name="url" placeholder="Enter your URL here">
            <button type="submit">Shorten</button>
        </form>
        @if ($errors->any())
            <div class="error">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        @if (session('shortened'))
            <p>Your shortened URL: <a href="{{ session('shortened') }}">{{ session('shortened') }}</a></p>
        @endif
    </div>
</body>
</html>
