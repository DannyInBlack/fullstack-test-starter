<?php

require_once __DIR__ . '/../vendor/autoload.php';

// Define the base path of your project
$basePath = '/fullstack-test-starter';

// Remove the base path from the REQUEST_URI
$uri = $_SERVER['REQUEST_URI'];
if (strpos($uri, $basePath) === 0) {
    $uri = substr($uri, strlen($basePath));
}

// Remove query string from the URI (if present)
$uri = strtok($uri, '?');

$dispatcher = FastRoute\simpleDispatcher(function(FastRoute\RouteCollector $r) {
    $r->post('/graphql', [App\Controller\GraphQL::class, 'handle']);
});

$routeInfo = $dispatcher->dispatch(
    $_SERVER['REQUEST_METHOD'],
    $uri // Use the modified URI
);

switch ($routeInfo[0]) {
    case FastRoute\Dispatcher::NOT_FOUND:
        // ... 404 Not Found
        echo '404 Not Found: ' . $_SERVER['REQUEST_URI'];
        break;
    case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
        $allowedMethods = $routeInfo[1];
        // ... 405 Method Not Allowed
        echo '405 Method Not Allowed';
        break;
    case FastRoute\Dispatcher::FOUND:
        $handler = $routeInfo[1];
        $vars = $routeInfo[2];
        // Call the handler
        echo call_user_func($handler, $vars);
        break;
}