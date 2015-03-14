<?php

// router init
$app->profiler()->createTimer('Initializing Router and adding routes');
$app->router = new \Naga\Core\Routing\Router($app->request());
$app->router->addRoutes($app->config('routes')->toArray());
$app->profiler()->stopTimer('Initializing Router and adding routes');