<?php

// Set your cached access token. Remember to replace $_SESSION with a
// real database or memcached.
session_start();

$client = new Google_Client();
$client->setApplicationName('Google+ PHP Starter Application');
// Visit https://code.google.com/apis/console?api=plus to generate your
// client id, client secret, and to register your redirect uri.
$client->setClientId($conf['Google_PlusService']['ClientId']);
$client->setClientSecret($conf['Google_PlusService']['ClientSecret']);
$client->setRedirectUri($conf['Google_PlusService']['RedirectUri']);
$client->setDeveloperKey($conf['Google_PlusService']['DeveloperKey']);
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
    print 'Me: <pre>' . print_r($me, true) . '</pre>';

    // We're not done yet. Remember to update the cached access token.
    // Remember to replace $_SESSION with a real database or memcached.
    $_SESSION['token'] = $client->getAccessToken();
} else {
    $authUrl = $client->createAuthUrl();
    print "<a href='$authUrl'>Connect Me!</a>";
}