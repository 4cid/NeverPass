<?php

// core
require_once __DIR__ . '/../bootstrap.php';

$client = $container->getGoogleClient();
$session = $container->getSession();
$request = $container->getRequest();
if ($channelId = $request->get('channelId')) {
    $session->set('channelId', $channelId);
}
$plus = new Google_PlusService($client);

if (isset($_GET['code'])) {
    $client->authenticate();
    $session->set('gptoken', $client->getAccessToken());

    //$activities = $plus->activities->listActivities('me', 'public');
    //print 'Your Activities: <pre>' . print_r($activities, true) . '</pre>';
    $me = $plus->people->get('me');

    // Set current user to session
    $user = new \NeverPass\User();
    $user->setDisplayName($me['displayName']);
    $user->setImageUrl($me['image']['url']);
    $user->setId($me['id']);
    $session->set('currentuser', $user);

    $url = $container->getUrl();
    if ($channelId = $session->get('channelId')) {
        $url .= '/' . $channelId;
    }

    $response = new \Symfony\Component\HttpFoundation\RedirectResponse($url);
    $response->send();

} else {
    $response = new \Symfony\Component\HttpFoundation\RedirectResponse($client->createAuthUrl());
    $response->send();
}