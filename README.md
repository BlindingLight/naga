#Naga Framework

*Current version: 2.0a*

##Branches

Naga repository has two branches for every version.

```
vx.x         contains a usable application structure
x.x-core     contains only core directory content
```

##Requirements

In order to use Naga, your environment has to meet these requirements:

```
Apache       2.2+
             with mod_rewrite enabled
PHP          5.4+
             with enabled extensions: PDO, mb, pgsql
MySQL        5.1+
PostgreSQL   9.1+
```

##Installation

Create your project directory:
```
mkdir project
```

Get a copy of Naga from git:
```
git clone https://username@bitbucket.org/BlindingLight/naga-core.git .
cd project
```

If you don't have [composer](http://getcomposer.org) installed globally, download it:
```
curl -sS https://getcomposer.org/installer | php
To install it globally:
mv composer.phar /usr/local/bin/composer
```

Run composer install to download dependencies:
```
composer install
```
This will download [Twig](http://twig.sensiolabs.org) and [SwiftMailer](http://swiftmailer.org).

Create a virtual host entry in apache2 config and make it point to
```
project/public
```

You are ready to develop your first Naga project! :)

##Configuration

You can find configuration files in ```app/config``` directory.
Default files:
```
application.php             application config
cacheconnections.php        cache connection config
databases.php               database config
email.php                   email config
externalclasses.php         external classes config for autoloader
routes.php                  app routes
validation.php              validation rules (currently not working)
```

You can create as much php files as you want here, even in subdirectories. These files will be accessible
via ```App::config('filename')```. If a file is in a subdirectory, you can access it via ```App::config('directory.subdirectory.file')```.
You can create json files too.

##Routing

You can define application routes in ```app/config/routes.php```.

A self-explanatory example:

```php
return array(
	// url with params, every parameter must follow this pattern: {paramName|regexp}
	'test/{id|[0-9]+}' => array(
		/*
		 * you can generate link with this identifier via App::urlGenerator()->route('home', array('id' => 1))
		 * or {{ 'test'|url('id:' ~ something.id) }} in twig templates
		 * see UrlGenerator docs
		 */
		'as' => 'test',
		/*
		 * http methods: get, post, put, delete
		 * these can either be a closure or name of a function
		 * the function will be called with first argument containing route parameters
		 * parsed from uri
		 */
		'get' => '\App\Controller\TestController@getTest',
		'post' => function($params) {
			return 'These are our params: ' . implode(', ', $params);
		}
	)
)
```

##Actions, Controllers, Templates and Views

TODO

##Assets

TODO