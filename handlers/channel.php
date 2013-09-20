<?php

// core
require_once __DIR__ . '/../bootstrap.php';

$request = $container->getRequest();
$user = $container->getCurrentUser();

if ($channelId = $request->get('id')) {
    // Channel available
    $channel = \NeverPass\Channel::getCached($channelId);
    $channel->addUser($user);

    $location = new \NeverPass\Location(10, 20, 30, $user->getId());
    $channel->addLocation($location);

    $channel->save();
    $response = new \Symfony\Component\HttpFoundation\JsonResponse($channel->toArray());
    $response->send();
} else {
    // Channel init
    $channel = new \NeverPass\Channel();
    $channel->addUser($user);
    $channel->save();
    $response = new \Symfony\Component\HttpFoundation\RedirectResponse('http://neverpass.de:8080/channel?id=' . $channel->getId());
    $response->send();
}