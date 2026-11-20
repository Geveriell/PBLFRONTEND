<?php
declare(strict_types=1);

require __DIR__ . '/config/bootstrap.php';

$routes = require __DIR__ . '/config/routes.php';
$page   = (string) ($_GET['page'] ?? 'menu');

if (!isset($routes[$page])) {
    http_response_code(404);
    render('errors/404', ['title' => 'Tidak ditemukan', 'css' => ['menu'], 'bodyClass' => 'wide']);
    exit;
}

$route = $routes[$page];
$ctx   = ['error' => null];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($route['post'])) {
    $ctx['error'] = ($route['post'])();
}

$data = isset($route['data']) ? ($route['data'])($ctx) : [];
$meta = array_diff_key($route, ['view' => 1, 'data' => 1, 'post' => 1]);

render($route['view'], $data + $meta);
