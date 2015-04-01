## Files

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

## Accessing config properties

You can access config file contents like:    
```php
App::config('filename')
```    
This returns a ConfigBag instance containing properties set in filename.php or filename.json.    

If a file is in a subdirectory, you can access it like:    
```php
App::config('directory.subdirectory.filename')
```    

You can access config properties directly like:     
```php
App::config('directory.subdirectory.filename.item.property.array.0.subProperty')
```  
This will return the value of 'subProperty' property.

## Application config

All of the properties below are mandatory.

#### timezone <span class="small">[required]</span>
Sets site's timezone.

#### debug
Sets debug mode. If it's true, Naga enables profiling and logging and sends profiler and log info to clients.
Turn this option off in production environment.

#### errorReportingLevel

Sets error reporting level (```error_reporting(App::config('application.errorReportingLevel'));```).
Set this to 0 in production environment.

#### autoStartSession

If true Naga calls ```$app->session()->start()``` automatically. If disabled, you have to manually start the session.
It's suitable for reverse-proxy caching (like Varnish), because you can start the session when a sign up request
comes in and passes validation. This way the content can be cached until user logs in.

#### defaultRoute

Sets default route to execute when no route specified (client is on document root). If ```defaultRouteIfLoggedIn```
 is set this applies only to users who aren't logged in.

#### defaultRouteIfLoggedIn

Sets default route to execute when no route specified (client is on document root). This applies to logged in
users only.

#### resourceRoot

Sets resource root. This value is used by ```UrlGenerator``` when you call ```App::urlGenerator()->resource()```.  

Example:  
```php
// resourceRoot = /assets/
echo App::urlGenerator()->resource('image/test.png') . "\n";
// resourceRoot = http://static.example.com/
echo App::urlGenerator()->resource('image/test.png');
```
output:  
```markdown
/assets/image/test.png
http://static.example.com/image/test.png
```

## Cache config

## CLI config

## Database config

## Email config

## External classes config

## Routes config

## Twig config

## Validation config
