<?php

// core
require_once __DIR__ . '/../bootstrap.php';

$client = $container->getGoogleClient();
$session = $container->getSession();
$plus = new Google_PlusService($client);

if (isset($_GET['code'])) {
    $client->authenticate();
    $session->set('gptoken', $client->getAccessToken());
    $redirect = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'];
    $response = new \Symfony\Component\HttpFoundation\RedirectResponse(filter_var($redirect, FILTER_SANITIZE_URL));
    $response->send();
} else {
    if ($session->has('gptoken')) {
        $client->setAccessToken($session->get('gptoken'));
    }

    if ($client->getAccessToken()) {
        //$activities = $plus->activities->listActivities('me', 'public');
        //print 'Your Activities: <pre>' . print_r($activities, true) . '</pre>';
        $me = $plus->people->get('me');

        // We're not done yet. Remember to update the cached access token.
        // Remember to replace $_SESSION with a real database or memcached.
        $session->set('gptoken', $client->getAccessToken());

        // Set current user to session
        $user = new \NeverPass\User();
        $user->setDisplayName($me['displayName']);
        $user->setImageUrl($me['image']['url']);
        $user->setId($me['id']);
        $session->set('currentuser', $user);

        $response = new \Symfony\Component\HttpFoundation\RedirectResponse($container->getUrl());
        $response->send();

    } else {
        $response = new \Symfony\Component\HttpFoundation\RedirectResponse($client->createAuthUrl());
        $response->send();
    }
}