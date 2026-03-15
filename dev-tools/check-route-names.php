<?php

$content = file_get_contents('route-list.json');
$json = json_decode($content, true);

$routes = $json['routes'] ?? $json ?? [];

$counts = [];

foreach ($routes as $route) {

    $name = $route['name'] ?? null;

    if ($name) {

        if (!isset($counts[$name])) {
            $counts[$name] = 0;
        }

        $counts[$name]++;
    }
}

echo "Duplicate Route Names:\n\n";

foreach ($counts as $name => $count) {
    if ($count > 1) {
        echo $count . "x  " . $name . "\n";
    }
}

echo "\nDone.\n";
