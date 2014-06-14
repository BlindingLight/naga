<?php

require_once(__DIR__ . '/../core/iComponent.php');
require_once(__DIR__ . '/../core/nComponent.php');
require_once(__DIR__ . '/../core/Autoloader.php');

$autoloader = new \Naga\Core\Autoloader();
$autoloader->setRootDirectory(__DIR__ . '/..');

spl_autoload_register(
	function($className) use($autoloader)
	{
		$autoloader->autoload($className);
	},
	true,
	true
);

// instantiating App
$app = new App\App();

// DON'T CHANGE THE ATTRIBUTE NAMES IF YOU WANT TO USE THE PREDEFINED ACCESSOR STATIC METHODS

// events
$app->events = new \Naga\Core\Event\Events();

// instantiating FileSystem
$app->fileSystem = new \Naga\Core\FileSystem\FileSystem();

// config init
$app->config = new \Naga\Core\Config\Config($app->fileSystem());
$app->config()->getFilesInDirectory(__DIR__ . '/../app/config', 'json');
$app->config()->getFilesInDirectory(__DIR__ . '/../app/config', 'php');

// enable profiling
if ($app->config('application')->get('debug'))
	\Naga\Core\Debug\Profiler::enableGlobally();

$app->logger()->notice('Note: Bootstrap time is measured after config, file and event systems are loaded.');
$app->profiler()->createTimer('Bootstrap time');

// setting timezone
$app->profiler()->createTimer('Setting timezone');
if (!date_default_timezone_set($app->config('application')->get('timezone')))
	date_default_timezone_set('UTC');
$app->profiler()->stopTimer('Setting timezone');

// adding external classes to autoloader
$app->profiler()->createTimer('Configuring external classes');
$autoloader->addExternalClasses(
	$app->config('externalclasses')->get('classes')
);
$autoloader->addExternalResolvers(
	$app->config('externalclasses')->get('resolvers')
);
$app->profiler()->stopTimer('Configuring external classes');

// session init
$app->profiler()->createTimer('Initializing SessionManager');
$app->session = new \Naga\Core\Session\SessionManager(new \Naga\Core\Session\Storage\Native());
$app->profiler()->stopTimer('Initializing SessionManager');

// hasher init
$app->profiler()->createTimer('Initializing Hasher');
$app->hasher = new \Naga\Core\Hashing\Hasher();
$app->hasher()->setAlgorithm(new \Naga\Core\Hashing\Algorithm\BaseSha1());
$app->profiler()->stopTimer('Initializing Hasher');

// auth init
$app->profiler()->createTimer('Initializing Auth');
$app->auth = new \Naga\Core\Auth\Auth($app->session()->storage());
$app->profiler()->stopTimer('Initializing Auth');

// request init
$app->profiler()->createTimer('Initializing Request');
$app->request = new \Naga\Core\Request\Request();
$app->profiler()->stopTimer('Initializing Request');

// input init
$app->profiler()->createTimer('Initializing Input');
$app->input = new \Naga\Core\Request\Input($app->session()->storage(), $app->fileSystem());
$app->profiler()->stopTimer('Initializing Input');

// cookie init
$app->profiler()->createTimer('Initializing Cookie and SecureCookie');
$app->cookie = new \Naga\Core\Cookie\Cookie();
$app->securecookie = new \Naga\Core\Cookie\SecureCookie();
$app->profiler()->stopTimer('Initializing Cookie and SecureCookie');

// router init
$app->profiler()->createTimer('Initializing Router and adding routes');
$app->router = new \Naga\Core\Routing\Router($app->request());
$app->router->addRoutes($app->config('routes')->toArray());
$app->profiler()->stopTimer('Initializing Router and adding routes');

// url generator init
$app->profiler()->createTimer('Initializing UrlGenerator');
$app->urlgenerator = new \Naga\Core\Routing\UrlGenerator(
	$app->config('routes')->toArray(),
	$app->request(),
	$app->config('application')->get('resourceRoot')
);
$app->profiler()->stopTimer('Initializing UrlGenerator');

// cache connections
$app->profiler()->createTimer('Initializing CacheManager and adding cache connections');
$app->cache = new \Naga\Core\Cache\CacheManager();
if ($app->config()->exists('cacheconnections'))
{
	$app->cache->addConnections(
		$app->cache->getConnectionsFromConfigArray(
			$app->config('cacheconnections')->toArray()
		)
	);
}
$app->profiler()->stopTimer('Initializing CacheManager and adding cache connections');

// database connections
$app->profiler()->createTimer('Initializing DatabaseManager and adding database connections');
$app->database = new Naga\Core\Database\DatabaseManager();
if ($app->config()->exists('databases'))
{
	$app->database(null)->addConnections(
		$app->database(null)->getConnectionsFromConfigArray(
			$app->config('databases')->toArray()
		)
	);
}
$app->profiler()->stopTimer('Initializing DatabaseManager and adding database connections');

// SwiftMailer config
$app->profiler()->createTimer('Initializing SwiftMailer');
require_once(__DIR__ . '/../vendor/swiftmailer/swiftmailer/lib/swift_init.php');
Swift::init(function()
{
	Swift_Preferences::getInstance()->setCharset('UTF-8');
});
$app->profiler()->stopTimer('Initializing SwiftMailer');

// adding email connections
$app->profiler()->createTimer('Initializing Email and adding email connections');
$app->email = new \Naga\Core\Email\Email();
if ($app->config()->exists('email'))
{
	foreach ($app->config('email')->toArray() as $connectionName => $props)
	{
		$className = $props->connectionClass;
		$conn = new $className((object)$props);
		$app->email()->addConnection($connectionName, $conn);
	}
}
$app->profiler()->stopTimer('Initializing Email and adding email connections');

// localization init
$app->profiler()->createTimer('Initializing Localization');
$app->localization = new \Naga\Core\Localization\Localization();
$app->profiler()->stopTimer('Initializing Localization');

// facades init, setting Application instance as global container
$app->profiler()->createTimer('Initializing facades');
\Naga\Core\Facade\Facade::setContainer($app);
$app->profiler()->stopTimer('Initializing facades');

$app->profiler()->stopTimer('Bootstrap time');