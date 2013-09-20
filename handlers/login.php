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
    header('Location: ' . filter_var($redirect, FILTER_SANITIZE_URL));
}

if ($session->has('gptoken')) {
    $client->setAccessToken($session->get('gptoken'));
}

if ($client->getAccessToken()) {
    //$activities = $plus->activities->listActivities('me', 'public');
    //print 'Your Activities: <pre>' . print_r($activities, true) . '</pre>';

    $me = $plus->people->get('me');
    echo '<h1>', $me['displayName'], '</h1>';
    echo '<img src="' . $me['image']['url'] . '"/>';
    echo '<p><a href="/logout">Logout</a></p>';

    // We're not done yet. Remember to update the cached access token.
    // Remember to replace $_SESSION with a real database or memcached.
    $session->set('gptoken', $client->getAccessToken());

    // Set current user to session
    $user = new \NeverPass\User();
    $user->setDisplayName($me['displayName']);
    $user->setImageUrl($me['image']['url']);
    $user->setId($me['id']);
    $session->set('currentuser', $user);

} else {
    $authUrl = $client->createAuthUrl();
    echo sprintf('<a href="%s">Connect Me!</a>', $authUrl);
}