<?php

// core
require_once __DIR__ . '/../bootstrap.php';

$response = new \Symfony\Component\HttpFoundation\Response('Not Found.', 404);
$response->send();