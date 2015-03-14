<?php

// instantiating app
$app = new App\App();

// starting bootstrap timer
$app->logger()->notice('Note: Bootstrap time is measured after autoloader is loaded.');
$app->profiler()->createTimer('Bootstrap time');