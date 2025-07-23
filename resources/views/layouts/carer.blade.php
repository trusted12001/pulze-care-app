<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Carer Panel</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body class="bg-gray-50">
    <header class="bg-indigo-600 text-white p-4">
        <h1>Pulze - Carer Panel</h1>
        <nav>
            <a href="{{ url('/care/feed') }}" class="text-white">Care Feed</a>
        </nav>
    </header>

    <main class="p-6">
        @yield('content')
    </main>
</body>
</html>
