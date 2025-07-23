<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Panel</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body class="bg-white">
    <header class="bg-green-800 text-white p-4">
        <h1>Pulze - Admin Area</h1>
        <nav>
            <a href="{{ url('/operations') }}" class="text-white">Operations</a>
        </nav>
    </header>

    <main class="p-6">
        @yield('content')
    </main>
</body>
</html>
