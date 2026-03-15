<?php

$content = file_get_contents('route-list.json');
$json = json_decode($content, true);

$routes = $json['routes'] ?? $json ?? [];

echo "ROUTE GROUP AUDIT\n";
echo "=================\n\n";

foreach ($routes as $route) {
    $uri = $route['uri'] ?? '';
    $name = $route['name'] ?? '[no name]';
    $action = $route['action'] ?? '[no action]';

    $middleware = $route['middleware'] ?? [];
    if (is_string($middleware)) {
        $middleware = array_map('trim', explode(',', $middleware));
    }

    if (!is_array($middleware)) {
        $middleware = [];
    }

    $topPrefix = explode('/', trim($uri, '/'))[0] ?? '';

    $isSuperAdmin = str_contains($name, 'super_admin.') || $topPrefix === 'super-admin';
    $isAdmin      = str_contains($name, 'admin.') || $topPrefix === 'admin';
    $isCarer      = str_contains($name, 'carer.') || $topPrefix === 'carer';

    $hasAuth = in_array('auth', $middleware);
    $hasWeb  = in_array('web', $middleware);

    $issues = [];

    if (($isSuperAdmin || $isAdmin || $isCarer) && !$hasAuth) {
        $issues[] = 'Missing auth middleware';
    }

    if (($isSuperAdmin || $isAdmin || $isCarer) && !$hasWeb) {
        $issues[] = 'Missing web middleware';
    }

    if (str_contains($name, 'super_admin.') && $topPrefix !== 'super-admin') {
        $issues[] = 'super_admin name but URI not under /super-admin';
    }

    if (str_contains($name, 'admin.') && $topPrefix !== 'admin') {
        $issues[] = 'admin name but URI not under /admin';
    }

    if (str_contains($name, 'carer.') && $topPrefix !== 'carer') {
        $issues[] = 'carer name but URI not under /carer';
    }

    if ($topPrefix === 'super-admin' && !str_contains($name, 'super_admin.')) {
        $issues[] = 'URI under /super-admin but name prefix not super_admin.';
    }

    if ($topPrefix === 'admin' && !str_contains($name, 'admin.')) {
        $issues[] = 'URI under /admin but name prefix not admin.';
    }

    if ($topPrefix === 'carer' && !str_contains($name, 'carer.')) {
        $issues[] = 'URI under /carer but name prefix not carer.';
    }

    if (!empty($issues)) {
        echo "URI: {$uri}\n";
        echo "Name: {$name}\n";
        echo "Action: {$action}\n";
        echo "Middleware: " . implode(', ', $middleware) . "\n";
        echo "Issues:\n";
        foreach ($issues as $issue) {
            echo "  - {$issue}\n";
        }
        echo "\n";
    }
}

echo "Done.\n";
