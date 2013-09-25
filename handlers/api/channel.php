<?php

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
// Close session
$container->getSession()->save();
session_write_close();

// Request
$request = $container->getRequest();

// Init Channel
if ($channelId = $request->get('id')) {
    // Channel available
    $channel = \NeverPass\Channel::getCached($channelId, null, $container->getMySQL());
} else {
    $channel = new \NeverPass\Channel();
}

$save = false;

// Add User
if (!array_key_exists($user->getId(), $channel->getUsers())) {
    $channel->setTimestamp(time());
    $channel->addUser($user);
    $save = true;
}

// Init Location
$longitude = $request->get('lon');
$latitude = $request->get('lat');
$heading = $request->get('hdg');
$accuracy = $request->get('acc');

if (strlen($longitude) && strlen($latitude) && strlen($heading) && strlen($accuracy)) {
    $location = new \NeverPass\Location($heading, $latitude, $longitude, $accuracy, $user->getId());
    // is location changing?
    if (array_key_exists($user->getId(), $channel->getLocations())) {
        if ($location->getHash() == $channel->getLocations()[$user->getId()]->getHash()) {
            $location = false;
        }
    }
    if ($location) {
        $channel->addLocation($location);
        $channel->setTimestamp(time());
        $save = true;
    }
}
// Save channel
if ($save) {
    $channel->save(null, $container->getMySQL());
}

// Long polling
if (($timestamp = $request->get('timestamp')) && ($channelId = $request->get('id'))) {
    $sec = 0;
    while ($channel->getTimestamp() <= $timestamp) {
        if ($sec >= 20) break;
        sleep(1);
        $channel = \NeverPass\Channel::getCached($channelId);
        $sec += 1;
    }
}

// create response
$data = $channel->toArray();
$data['url'] = $container->getUrl() . '/api/channel?id=' . $channel->getId();
$response = new \Symfony\Component\HttpFoundation\JsonResponse($data);
$response->send();