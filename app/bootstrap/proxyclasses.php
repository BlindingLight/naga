<?php

// proxy classes init, setting Application instance as global container
$app->profiler()->createTimer('Initializing proxy classes');
\Naga\Core\Proxy\Proxy::setContainer($app);
$app->profiler()->stopTimer('Initializing proxy classes');