<?php

// Set your cached access token. Remember to replace $_SESSION with a
// real database or memcached.
session_start();

$conf = $container['config']->get('Google_PlusService');

$client = new Google_Client();
$client->setApplicationName('NeverPass');
// Visit https://code.google.com/apis/console?api=plus to generate your
// client id, client secret, and to register your redirect uri.
$client->setClientId($conf['ClientId']);
$client->setClientSecret($conf['ClientSecret']);
$client->setRedirectUri($conf['RedirectUri']);
$client->setDeveloperKey($conf['DeveloperKey']);
$plus = new Google_PlusService($client);

if (isset($_GET['code'])) {
    $client->authenticate();
    $_SESSION['token'] = $client->getAccessToken();
    $redirect = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'];
    header('Location: ' . filter_var($redirect, FILTER_SANITIZE_URL));
}

if (isset($_SESSION['token'])) {
    $client->setAccessToken($_SESSION['token']);
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
    $_SESSION['token'] = $client->getAccessToken();
} else {
    $authUrl = $client->createAuthUrl();
    echo sprintf('<a href="%s">Connect Me!</a>', $authUrl);
}