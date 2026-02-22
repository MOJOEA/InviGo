<?php
declare(strict_types=1);
function normalizeUri(string $uri): string
{
    $uri = preg_replace('/\?.*/', '', $uri);
    $uri = strtolower(trim($uri, '/'));
    return $uri == INDEX_URI ? INDEX_ROUTE : $uri;
}
function notFound()
{
    http_response_code(404);
    renderView('404');
    exit;
}
function getFilePath(string $uri): string
{
    if (preg_match('/^admin\/(\w+)$/', $uri, $matches)) {
        return ROUTE_DIR . '/admin/' . $matches[1] . '.php';
    }
    if ($uri === 'admin') {
        return ROUTE_DIR . '/admin/index.php';
    }
    if (preg_match('/^events\/(\d+)$/', $uri, $matches)) {
        return ROUTE_DIR . '/events/view.php';
    }
    if (preg_match('/^events\/\d+\/(\w+)$/', $uri, $matches)) {
        return ROUTE_DIR . '/events/' . $matches[1] . '.php';
    }
    if (preg_match('/^events\/(\w+)$/', $uri, $matches)) {
        return ROUTE_DIR . '/events/' . $matches[1] . '.php';
    }
    return ROUTE_DIR . '/' . normalizeUri($uri) . '.php';
}
function extractRouteParams(string $uri): array
{
    $params = [];
    if (preg_match('/^events\/(\d+)/', $uri, $matches)) {
        $params['id'] = (int)$matches[1];
    }
    return $params;
}
function dispatch(string $uri, string $method): void
{
    $uri = normalizeUri($uri);
    if (!in_array(strtoupper($method), ALLOW_METHODS)) {
        notFound();
    }
    $params = extractRouteParams($uri);
    foreach ($params as $key => $value) {
        $_GET[$key] = $value;
    }
    $filePath = getFilePath($uri);
    if (file_exists($filePath)) {
        include($filePath);
        return;
    } else {
        notFound();
    }
}
