<?php

$app->events = new \Naga\Core\Event\Events();

// registering Naga core event listeners
new \Naga\Core\Middleware\Csrf($app);