<?php

// clear session
$container->getSession()->clear();

$response = new \Symfony\Component\HttpFoundation\RedirectResponse($container->getUrl());
$response->send();