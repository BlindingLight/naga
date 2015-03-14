<?php

// cookie init
$app->profiler()->createTimer('Initializing Cookie and SecureCookie');
$app->cookie = new \Naga\Core\Cookie\Cookie();
$app->securecookie = new \Naga\Core\Cookie\SecureCookie();
$app->profiler()->stopTimer('Initializing Cookie and SecureCookie');