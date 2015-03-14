<?php

// request init
$app->profiler()->createTimer('Initializing Request');
$app->request = new \Naga\Core\Request\Request();
$app->profiler()->stopTimer('Initializing Request');