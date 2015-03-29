# Naga Framework

Current version: 2.1b

## Table of Contents

1. [Requirements](#requirements)
2. [Installation](#installation)
3. [Configuration](#configuration)
4. [Bootstrap](#bootstrap)
5. [Routing](#routing)

## Requirements

In order to use Naga, your environment has to meet these requirements:  
```
Apache       2.2+  
             with mod_rewrite enabled  
PHP          5.4+  
             with PDO and enabled extensions: mbstring, pgsql
```

## Installation

- Create your project directory: ```mkdir project```  
- Get a copy of Naga from git:  
```
git clone https://github.com/BlindingLight/naga-framework.git project  
cd project
```
- If you don't have [Composer](http://getcomposer.org) installed globally, download it:    
```
curl -sS https://getcomposer.org/installer | php
```    
To install it globally:    
```
mv composer.phar /usr/local/bin/composer
```
- Run composer install to download dependencies:    
```composer install```  
This will download Naga framework dependencies.    
- Create a virtual host entry in apache2 config and make it point to ```public```

You are ready to start your first Naga project with love. <3

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

You can create as many php or json files as you want, even in subdirectories.    
You can access config file contents like:    
```
App::config('filename')
```    
This returns a ConfigBag instance containing properties set in filename.php or filename.json.    

If a file is in a subdirectory, you can access it like:    
```
App::config('directory.subdirectory.filename')
```    

You can access config properties directly like:     
```
App::config('directory.subdirectory.filename.item.property.array.0.subProperty')
```  
This will return the value of 'subProperty' property.

## Bootstrap

Run this code every time you change code in any of the php files in ```app/bootstrap``` directory:  
```
php app/bootstrap.php update
```

Write your custom code to ```custom.php```. This file will be included last. You can change other bootstrap files too, but keep in mind if a new Naga framework bootstrap update comes, your will have to merge the changes.

## Routing

You can define your routes in ```app/config/routes.php```.

A self-explanatory example:  
```php
return array(
	// url with params, every parameter must follow this pattern: {paramName|regexp}
	'test/{id|[0-9]+}' => array(
		/*
		 * you can generate links with this identifier via App::urlGenerator()->route('test', array('id' => 1))
		 * or {{ 'test'|url('id:' ~ something.id) }} in twig templates
		 * see UrlGenerator docs
		 */
		'as' => 'test',
		// you can use this to create aliases to routes, this way you don't have
		// to write method names or callables multiple times for different urls
		'sameAs' => 'other-route',
		/*
		 * domain restriction of route
		 * you can use wildcards
		 */
		'domain' => '*.example.com',
		/*
		 * http methods: get, post, put, delete
		 * these can either be a closure or name of a method
		 * the method will be called with first argument containing route parameters
		 * parsed from uri
		 */
		// you can use . instead of \
		'get' => '\App\Controller\TestController@getTest',
		'post' => function($params) {
			return 'These are our params: ' . implode(', ', $params);
		}
	)
)
```
