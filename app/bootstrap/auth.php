<?php

// auth init
$app->profiler()->createTimer('Initializing Auth');
$app->auth = new \Naga\Core\Auth\Auth($app->session()->storage());
$app->profiler()->stopTimer('Initializing Auth');