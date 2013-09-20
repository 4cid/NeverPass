<?php require_once __DIR__ . '/../bootstrap.php'; ?><!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    <!-- Bootstrap -->
    <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap-theme.min.css">
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>
<div class="navbar navbar-inverse navbar-fixed-top">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="#">NeverPass</a>
        </div>
        <div class="collapse navbar-collapse">
            <ul class="nav navbar-nav">
                <li class="active"><a href="#home" data-toggle="tab">Home</a></li>
                <li><a href="#about" data-toggle="tab">About</a></li>
                <li><a href="#contact" data-toggle="tab">Contact</a></li>
            </ul>
            <div class="navbar-form navbar-left">
                <button class="btn btn-default">Start Channel</button>
            </div>
            <ul class="nav navbar-nav navbar-right">
                <?php if($container->isUserLoggedIn()) { ?>
                    <li><a href="/logout">Logout</a></li>
                    <li><img width="100%" src="<?php echo $container->getCurrentUser()->getImageUrl() ?>" ></li>
                <?php } else { ?>
                    <li><a href="/login">Login</a></li>
                <?php } ?>
            </ul>
        </div>
        <!--/.nav-collapse -->
    </div>
</div>

<div class="container container-slim">

    <div class="tab-content">
        <div class="tab-pane map active" id="home">

            <div><pre id="log"></pre></div>
            <div id="the-map" style="background-color: #444;"></div>

        </div>
        <div class="tab-pane" id="about">

            <div class="jumbotron">
                <h1>About</h1>
                <p>Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet.</p>
            </div>

        </div>
        <div class="tab-pane" id="contact">

            <div class="jumbotron">
                <h1>Contact</h1>
                <p>Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet.</p>
            </div>

        </div>
    </div>

</div>
<script src="//ajax.googleapis.com/ajax/libs/jquery/2.0.3/jquery.min.js"></script>
<script src="//netdna.bootstrapcdn.com/bootstrap/3.0.0/js/bootstrap.min.js"></script>
<script src="//maps.googleapis.com/maps/api/js?key=<?= $container->getConfig()->get('Google_PlusService.DeveloperKey') ?>&sensor=true"></script>
<script src="/js/app.js"></script>
</body>
</html>