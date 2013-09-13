<?php

// composer Autoload
require_once __DIR__ . '/vendor/autoload.php';

// Dependency Injection Container
$container = new \NeverPass\Container();

// Setup Config
$container['config'] = $container->share(function ($c) {
    if (!file_exists(__DIR__ . '/config/conf.yml')) throw new Exception('conf.yml is missing');
    return new \NeverPass\Config(__DIR__ . '/config/conf.yml', new Symfony\Component\Yaml\Parser);
});
