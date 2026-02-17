<?php

function dispatch(string $uri, string $method): void
{
    // Remove query string
    $uri = parse_url($uri, PHP_URL_PATH);

    // Remove leading slash
    $uri = ltrim($uri, '/');

    // Default route
    if ($uri === '' || $uri === 'index.php') {
        $uri = 'home';
    }

    // Handle routes with parameters (e.g., /enroll/1)
    $parts = explode('/', $uri);
    $routeFile = $parts[0];
    $param = $parts[1] ?? null;

    $routePath = ROUTES_DIR . '/' . $routeFile . '.php';

    if (file_exists($routePath)) {
        if ($param) {
            // Pass parameter to route
            $_GET['id'] = $param;
        }
        require_once $routePath;
    } else {
        // 404
        renderView('404');
    }
}
?>
