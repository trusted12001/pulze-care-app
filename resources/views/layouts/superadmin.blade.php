<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Super Admin Panel</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body class="bg-gray-100">
    <header class="bg-blue-900 text-white p-4">
        <h1>Pulze - Super Admin Dashboard</h1>
        <nav>
            <a href="{{ route('admin.sadmins.index') }}" class="text-white">Manage Tenants</a>
        </nav>
    </header>

    <main class="p-6">
        @yield('content')
    </main>
</body>
</html>
