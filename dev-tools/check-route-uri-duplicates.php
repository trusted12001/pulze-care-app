<?php

$content = file_get_contents('route-list.json');
$json = json_decode($content, true);

$routes = $json['routes'] ?? $json ?? [];

$counts = [];
$details = [];

foreach ($routes as $route) {
    $methods = $route['method'] ?? $route['methods'] ?? [];
    $uri = $route['uri'] ?? '';

    if (is_string($methods)) {
        $methods = array_map('trim', explode('|', $methods));
    }

    if (!is_array($methods)) {
        $methods = [];
    }

    sort($methods);

    $key = implode('|', $methods) . '  ' . $uri;

    if (!isset($counts[$key])) {
        $counts[$key] = 0;
        $details[$key] = [];
    }

    $counts[$key]++;

    $details[$key][] = [
        'name' => $route['name'] ?? '',
        'action' => $route['action'] ?? '',
    ];
}

echo "Duplicate Method + URI Routes:\n\n";

$found = false;

foreach ($counts as $key => $count) {
    if ($count > 1) {
        $found = true;
        echo $count . "x  " . $key . "\n";

        foreach ($details[$key] as $item) {
            echo "    - name: " . ($item['name'] ?: '[no name]') . "\n";
            echo "      action: " . ($item['action'] ?: '[no action]') . "\n";
        }

        echo "\n";
    }
}

if (!$found) {
    echo "No duplicate Method + URI routes found.\n";
}

echo "\nDone.\n";
