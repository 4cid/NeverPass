<?php

// Composer Autoload
require_once __DIR__ . '/vendor/autoload.php';

// Need conf.yml
if (!file_exists(__DIR__ . '/config/conf.yml')) throw new Exception('conf.yml is missing');

// Load Config
$yaml = new Symfony\Component\Yaml\Parser();
$conf = $yaml->parse(file_get_contents(__DIR__ . '/config/conf.yml'));

// Google API Test
require_once __DIR__ . '/sample/google-api-php-client.php';