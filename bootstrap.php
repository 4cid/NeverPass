<?php

// composer Autoload
require_once __DIR__ . '/vendor/autoload.php';

// Setup Config
if (!file_exists(__DIR__ . '/config/conf.yml')) throw new Exception('conf.yml is missing');
$config = new \NeverPass\Config(__DIR__ . '/config/conf.yml', new Symfony\Component\Yaml\Parser);