<?php

$content = file_get_contents('route-list.json');
$json = json_decode($content, true);

$routes = $json['routes'] ?? $json ?? [];

$counts = [];
$details = [];

foreach ($routes as $route) {
    $action = $route['action'] ?? '';

    if (!$action || str_contains($action, 'Closure')) {
        continue;
    }

    if (!isset($counts[$action])) {
        $counts[$action] = 0;
        $details[$action] = [];
    }

    $counts[$action]++;

    $methods = $route['method'] ?? $route['methods'] ?? [];
    if (is_array($methods)) {
        $methods = implode('|', $methods);
    }

    $details[$action][] = [
        'methods' => $methods,
        'uri' => $route['uri'] ?? '',
        'name' => $route['name'] ?? '',
    ];
}

echo "Repeated Controller Actions:\n\n";

$found = false;

foreach ($counts as $action => $count) {
    if ($count > 1) {
        $found = true;
        echo $count . "x  " . $action . "\n";

        foreach ($details[$action] as $item) {
            echo "    - " . ($item['methods'] ?: '[no methods]') . "  " . $item['uri'] . "\n";
            echo "      name: " . ($item['name'] ?: '[no name]') . "\n";
        }

        echo "\n";
    }
}

if (!$found) {
    echo "No repeated controller actions found.\n";
}

echo "\nDone.\n";
