<?php declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';


$basePath = '/fullstack-test-starter';

// remove base path
$uri = $_SERVER['REQUEST_URI'];
if (strpos($uri, $basePath) === 0) {
    $uri = substr($uri, strlen($basePath));
}

// remove query string from uri
$uri = strtok($uri, '?');


// handle CORS headers
header("Access-Control-Allow-Origin: *"); 
header("Access-Control-Allow-Methods: POST, GET, OPTIONS"); 
header("Access-Control-Allow-Headers: Content-Type, Authorization");

// handle options request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}


$dispatcher = FastRoute\simpleDispatcher(function(FastRoute\RouteCollector $r) {
    $r->post('/graphql', [App\Controller\GraphQL::class, 'handle']);
});

$routeInfo = $dispatcher->dispatch(
    $_SERVER['REQUEST_METHOD'],
    $uri // modified uri
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
        // echo query result
        echo call_user_func($handler, $vars);
        break;
}
