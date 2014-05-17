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

// enable profiling
// \Naga\Core\Debug\Profiler::enable();

// instantiating App
$app = new App\App();

// DON'T CHANGE THE ATTRIBUTE NAMES IF YOU WANT TO USE THE PREDEFINED ACCESSOR STATIC METHODS

// instantiating FileSystem
$app->fileSystem = new \Naga\Core\FileSystem\FileSystem();

// config init
$app->config = new \Naga\Core\Config\Config($app->fileSystem());
$app->config()->getFilesInDirectory(__DIR__ . '/../app/config', 'json');
$app->config()->getFilesInDirectory(__DIR__ . '/../app/config', 'php');

// setting timezone
if (!date_default_timezone_set($app->config('application')->get('timezone')))
	date_default_timezone_set('UTC');

// adding external classes to autoloader
$autoloader->addExternalClasses(
	$app->config('externalclasses')->get('classes')
);
$autoloader->addExternalResolvers(
	$app->config('externalclasses')->get('resolvers')
);
// session config
$app->session = new \Naga\Core\Session\SessionManager(new \Naga\Core\Session\Storage\Native());
// auth init
$app->auth = new \Naga\Core\Auth\Auth($app->session()->storage());
// request init
$app->request = new \Naga\Core\Request\Request();
// input init
$app->input = new \Naga\Core\Request\Input($app->session()->storage(), $app->fileSystem());
// cookie init
$app->cookie = new \Naga\Core\Cookie\Cookie();
$app->securecookie = new \Naga\Core\Cookie\SecureCookie();
// router init
$app->router = new \Naga\Core\Routing\Router($app->request());
$app->router->addRoutes($app->config('routes')->toArray());
// url generator init
$app->urlgenerator = new \Naga\Core\Routing\UrlGenerator(
	$app->config('routes')->toArray(),
	$app->request(),
	$app->config('application')->get('resourceRoot')
);
// cache connections
$app->cache = new \Naga\Core\Cache\CacheManager();
if ($app->config()->exists('cacheconnections'))
{
	$app->cache->addConnections(
		$app->cache->getConnectionsFromConfigArray(
			$app->config('cacheconnections')->toArray()
		)
	);
}

// database connections
$app->database = new Naga\Core\Database\DatabaseManager();
if ($app->config()->exists('databases'))
{
	$app->database(null)->addConnections(
		$app->database(null)->getConnectionsFromConfigArray(
			$app->config('databases')->toArray()
		)
	);
}

// SwiftMailer config
require_once(__DIR__ . '/../vendor/swiftmailer/swift_required.php');
Swift::init(function()
{
	Swift_Preferences::getInstance()->setCharset('UTF-8');
});

// creating email connections
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

// localization init
$app->localization = new \Naga\Core\Localization\Localization();

// setting default language
$app->localization()->setDefaultLanguage(1);
$app->localization()->setCurrentLanguage(1);