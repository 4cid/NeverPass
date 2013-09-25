<?php

// Include Core
require_once __DIR__ . '/../bootstrap.php';

// Simple Routing
try {
    $path = $container->getRequest()->getPathInfo();
    switch ($path) {
        case '/login':
            require_once __DIR__ . '/login.php';
            break;
        case '/logout':
            require_once __DIR__ . '/logout.php';
            break;
        case '/api/channel':
            require_once __DIR__ . '/api/channel.php';
            break;
        case '/':
            require_once __DIR__ . '/main.php';
            break;
        default:
            if (strlen($path) == 33 || $path == '/') {
                require_once __DIR__ . '/main.php';
            } else {
                $response = new \Symfony\Component\HttpFoundation\Response('Not Found.', 404);
                $response->send();
            }
            break;
    }
} catch (Exception $e) {
    ob_get_clean();
    throw $e;
}