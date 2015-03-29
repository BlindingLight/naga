<?php 

require_once __DIR__ . '/../../vendor/autoload.php';

$autoloader = new \Naga\Core\Autoloader();
$autoloader->setRootDirectory(__DIR__ . '/../../');

spl_autoload_register(
	function($className) use($autoloader)
	{
		$autoloader->autoload($className);
	},
	true,
	false
);

// instantiating app
$app = new App\App();

// starting bootstrap timer
$app->logger()->notice('Note: Bootstrap time is measured after autoloader is loaded.');
$app->profiler()->createTimer('Bootstrap time');

$app->events = new \Naga\Core\Event\Events();

$app->fileSystem = new \Naga\Core\FileSystem\FileSystem();

// config init
$app->profiler()->createTimer('Initializing Config');
$app->config = new \Naga\Core\Config\Config($app->fileSystem());
$app->config()->getFilesInDirectory(__DIR__ . '/../config', 'json');
$app->config()->getFilesInDirectory(__DIR__ . '/../config', 'php');

// adding external classes to autoloader
$app->profiler()->createTimer('Configuring external classes');
$autoloader->addExternalClasses(
	$app->config('externalclasses')->get('classes')
);
$autoloader->addExternalResolvers(
	$app->config('externalclasses')->get('resolvers')
);
$app->profiler()->stopTimer('Configuring external classes');

$app->profiler()->stopTimer('Initializing Config');

if ($app->config('application')->get('debug'))
{
	// enable profiling
	\Naga\Core\Debug\Profiler::enableGlobally();

	// display errors and error reporting
	ini_set('display_errors', 1);
	error_reporting(
		$app->config('application')->get('errorReportingLevel')
		? $app->config('application')->get('errorReportingLevel')
		: E_ALL | E_STRICT
	);

	// whoops init
	$whoops = new \Whoops\Run;
	$whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);
	$whoops->register();
}

// validator init
$app->validator = new \Naga\Core\Validation\Validator();

// session init
$app->profiler()->createTimer('Initializing SessionManager');
$app->session = new \Naga\Core\Session\SessionManager(new \Naga\Core\Session\Storage\Native());
if ($app->config('application')->get('autoStartSession'))
	$app->session()->start();

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

// checking if email layer is enabled
if ($app->config()->exists('email') && $app->config('email')->get('enabled'))
{
	// SwiftMailer config
	$app->profiler()->createTimer('Initializing SwiftMailer');
	require_once(__DIR__ . '/../../vendor/swiftmailer/swiftmailer/lib/swift_init.php');
	Swift::init(
		function ()
		{
			Swift_Preferences::getInstance()->setCharset('UTF-8');
		}
	);
	$app->profiler()->stopTimer('Initializing SwiftMailer');

	// adding email connections
	$app->profiler()->createTimer('Initializing Email and adding email connections');
	$app->email = new \Naga\Core\Email\Email();

	foreach ($app->config('email')->get('connections') as $connectionName => $props)
	{
		$className = $props->connectionClass;
		$app->profiler()->createTimer("Adding connection {$connectionName}.");
		$conn = new $className((object)$props);
		$app->profiler()->stopTimer("Adding connection {$connectionName}.");
		$app->email()->addConnection($connectionName, $conn);
	}

	$app->profiler()->stopTimer('Initializing Email and adding email connections');
}

// setting timezone
$app->profiler()->createTimer('Setting timezone');
if (!date_default_timezone_set($app->config('application')->get('timezone')))
	date_default_timezone_set('UTC');
$app->profiler()->stopTimer('Setting timezone');

// localization init
$app->profiler()->createTimer('Initializing Localization');
$app->localization = new \Naga\Core\Localization\Localization();
$app->profiler()->stopTimer('Initializing Localization');

// proxy classes init, setting Application instance as global container
$app->profiler()->createTimer('Initializing proxy classes');
\Naga\Core\Proxy\Proxy::setContainer($app);
$app->profiler()->stopTimer('Initializing proxy classes');

// place your custom bootstrap code here
$app->profiler()->stopTimer('Bootstrap time');