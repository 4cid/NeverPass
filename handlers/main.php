<?php

$request = $container->getRequest();
$container->getSession();
$uri = substr((string)$request->getRequestUri(), 1);
$channel = false;
if ($uri) {
    try {
        $channel = \NeverPass\Channel::getCached($uri);
    } catch (\Exception $e) {
        $response = new \Symfony\Component\HttpFoundation\RedirectResponse($container->getUrl());
        $response->send();
        exit;
    }
}
?><!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimum-scale=1.0, maximum-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    <!-- Bootstrap -->
    <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap-theme.min.css">
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>

<nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="/">NeverPass</a>
        </div>
        <div class="collapse navbar-collapse">
            <ul class="nav navbar-nav">
                <?php if ($container->isUserLoggedIn() && !$channel) { ?>
                <li>
                    <button class="btn btn-success navbar-btn" id="btn-start" style="margin-right: 5px">Start Channel</button>
                </li>
                <?php } ?>
                <li <?php if (!$container->isUserLoggedIn() || !$channel) { ?>class="hide"<?php } ?> id="g-share-btn">
                    <!-- https://developers.google.com/+/web/share/interactive -->
                    <button
                        class="g-interactivepost btn btn-success navbar-btn"
                        data-clientid="<?= $container->getConfig()->get('Google_PlusService.ClientId') ?>"
                        data-contenturl="<?= $container->getUrl() . $request->getRequestUri() ?>"
                        data-calltoactionlabel="INVITE"
                        data-prefilltext="Join my NeverPass channel!"
                        data-calltoactionurl="<?= $container->getUrl() . $request->getRequestUri() ?>"
                        data-cookiepolicy="single_host_origin">
                        Tell your friends
                    </button>
                </li>
                <li><a target="_blank" href="http://www.neverpass.me" >More about NeverPass</a></li>
            </ul>
            <?php if ($container->isUserLoggedIn()) { ?>
                <a class="navbar-right" href="/logout">
                    <button type="button" class="btn btn-warning navbar-btn">Logout</button>
                </a>
            <?php } else { ?>
                <a class="navbar-right" href="/login<?= $channel ? '?channelId=' . $channel->getId() : '' ?>">
                    <button type="button" class="btn btn-primary navbar-btn">Login</button>
                </a>
            <?php } ?>
        </div>
    </div>
</nav>

<div class="container container-slim">
    <div class="map">
        <div>
            <pre id="log"></pre>
        </div>
        <div id="the-map" style="background-color: #444;"></div>
    </div>
</div>

<!-- Modals -->
<div class="modal fade" id="modal-notLoggedIn" tabindex="-1" role="dialog" aria-labelledby="Not logged in" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Not logged in</h4>
            </div>
            <div class="modal-body">
                Please log in now!
            </div>
            <div class="modal-footer">
                <a href="/login<?= $channel ? '?channelId=' . $channel->getId() : '' ?>" type="button" class="btn btn-primary">Login</a>
            </div>
        </div>
    </div>
</div>

<!-- Scripts -->
<script src="//ajax.googleapis.com/ajax/libs/jquery/2.0.3/jquery.min.js"></script>
<script src="//netdna.bootstrapcdn.com/bootstrap/3.0.0/js/bootstrap.min.js"></script>
<script src="//maps.googleapis.com/maps/api/js?v=3.exp&key=<?= $container->getConfig()->get('Google_PlusService.DeveloperKey') ?>&sensor=true"></script>
<script src="/lib/history.js/scripts/bundled/html5/jquery.history.js"></script>
<script>
    var app = {
        channelId: '<?= $channel ? $channel->getId() : '' ?>'
    };
</script>
<script src="/js/channel.js"></script>
<script src="/js/app.js"></script>
<?php if($container->isUserLoggedIn()) { ?>
<script type="text/javascript">
    (function () {
        var po = document.createElement('script');
        po.type = 'text/javascript';
        po.async = true;
        po.src = 'https://apis.google.com/js/client:plusone.js';
        var s = document.getElementsByTagName('script')[0];
        s.parentNode.insertBefore(po, s);
    })();
</script>
<?php } ?>
</body>
</html>