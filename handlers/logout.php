<?php

// core
require_once __DIR__ . '/../bootstrap.php';

// clear session
$container->getSession()->clear();

header('Location: /');