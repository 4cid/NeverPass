<?php

// core
require_once __DIR__ . '/../../bootstrap.php';

$request = $container->getRequest();
$user = $container->getCurrentUser();

// Init Channel
if ($channelId = $request->get('id')) {
    // Channel available
    $channel = \NeverPass\Channel::getCached($channelId);
} else {
    $channel = new \NeverPass\Channel();
}

// Add User
$channel->addUser($user);

// Init Location
$longitude = $request->get('lon');
$latitude = $request->get('lat');
$heading = $request->get('hdg');

if (strlen($longitude) && strlen($latitude) && strlen($heading)) {
    $location = new \NeverPass\Location($heading, $latitude, $longitude, $user->getId());
    $channel->addLocation($location);
}
// Save channel to Memcached
$channel->save();

// create response
$data = $channel->toArray();
$data['url'] = $container->getUrl() . '/api/channel?id=' . $channel->getId();
$response = new \Symfony\Component\HttpFoundation\JsonResponse($data);
$response->send();