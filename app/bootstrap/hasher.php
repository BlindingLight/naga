<?php

// hasher init
$app->profiler()->createTimer('Initializing Hasher');
$app->hasher = new \Naga\Core\Hashing\Hasher();
$app->hasher()->setAlgorithm(new \Naga\Core\Hashing\Algorithm\BaseSha1());
$app->profiler()->stopTimer('Initializing Hasher');