<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Redirecting...</title>
    <meta http-equiv="refresh" content="0;url={{ route('login') }}" />
</head>
<body>
    Redirecting to <a href="{{ route('login') }}">login page</a>...
</body>
</html>