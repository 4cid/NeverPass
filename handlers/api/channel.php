<?php

// core
require_once __DIR__ . '/../../bootstrap.php';

// Check User LogIn
try {
    $user = $container->getCurrentUser();
} catch (\Exception $e) {
    if ($e instanceof \NeverPass\exception\LoginException) {
        $response = new \Symfony\Component\HttpFoundation\JsonResponse('Unauthorized', 401);
        $response->send();
        exit;
    } else {
        throw $e;
    }
}
// Request
$request = $container->getRequest();

// Init Channel
if ($channelId = $request->get('id')) {
    // Channel available
    $channel = \NeverPass\Channel::getCached($channelId);
} else {
    $channel = new \NeverPass\Channel();
}

// Add User
if (!array_key_exists($user->getId(), $channel->getUsers())) {
    $channel->setTimestamp(time());
    $channel->addUser($user);
}

// Init Location
$longitude = $request->get('lon');
$latitude = $request->get('lat');
$heading = $request->get('hdg');
$accuracy = $request->get('acc');

if (strlen($longitude) && strlen($latitude) && strlen($heading) && strlen($accuracy)) {
    $location = new \NeverPass\Location($heading, $latitude, $longitude, $accuracy, $user->getId());
    $channel->addLocation($location);
    $channel->setTimestamp(time());
}
// Save channel to Memcached
$channel->save();

// Long polling
if (($timestamp = $request->get('timestamp')) && ($channelId = $request->get('id'))) {
    $sec = 0;
    while ($channel->getTimestamp() <= $timestamp) {
        if ($sec >= 20) break;
        sleep(5);
        $channel = \NeverPass\Channel::getCached($channelId);
        $sec += 5;
    }
}

// create response
$data = $channel->toArray();
$data['url'] = $container->getUrl() . '/api/channel?id=' . $channel->getId();
$response = new \Symfony\Component\HttpFoundation\JsonResponse($data);
$response->send();