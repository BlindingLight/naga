<?php

// input init
$app->profiler()->createTimer('Initializing Input');
$app->input = new \Naga\Core\Request\Input($app->session()->storage(), $app->fileSystem());
$app->profiler()->stopTimer('Initializing Input');