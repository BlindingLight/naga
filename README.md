#Naga Framework

Current version: 2.1b

## Requirements

In order to use Naga, your environment has to meet these requirements:  
```
Apache       2.2+  
             with mod_rewrite enabled  
PHP          5.4+  
             with PDO and enabled extensions: mbstring, pgsql, mysql, sqlite 
MySQL        5.1+  
PostgreSQL   9.1+  
```

## Installation

- Create your project directory: ```mkdir project```  
- Get a copy of Naga from git:  
```
git clone https://username@bitbucket.org/BlindingLight/naga-core.git .  
cd project
```
- If you don't have [composer](http://getcomposer.org) installed globally, download it:    
```
curl -sS https://getcomposer.org/installer | php
To install it globally:
mv composer.phar /usr/local/bin/composer
```
- Run composer install to download dependencies: ```composer install```  
This will download [Twig](http://twig.sensiolabs.org), [SwiftMailer](http://swiftmailer.org) and Naga framework.
- Create a virtual host entry in apache2 config and make it point to ```public```

You are ready to develop your first Naga project! :)

## Configuration

You can find configuration files in ```app/config``` directory.  
Default files:  
```
application.php             application config
cacheconnections.php        cache connections config
cli.php                     command line config
databases.php               database config
email.php                   email config
externalclasses.php         external classes config for autoloader
routes.php                  app routes
twig.php                    Twig related configurations
validation.php              validation rules
```

You can create as many php or json files as you want, even in subdirectories. These configs will be accessible  
like ```App::config('filename')```. If a file is in a subdirectory, you can access it like ```App::config('directory.subdirectory.filename')```. You can access config properties directly like ```App::config('directory.subdirectory.filename.item.property.array.0.subProperty')```    

## Bootstrap

Run this code every time you change code in any of the php files in ```app/bootstrap``` directory:  
```
php app/bootstrap.php update
```

Write your custom code to ```custom.php```. This file will be included last.

## Routing

You can define your routes in ```app/config/routes.php```.

A self-explanatory example:  
```php
return array(
	// url with params, every parameter must follow this pattern: {paramName|regexp}
	'test/{id|[0-9]+}' => array(
		/*
		 * you can generate links with this identifier via App::urlGenerator()->route('home', array('id' => 1))
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
