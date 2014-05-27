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

TODO

##Routing

TODO

##Actions, Controllers, Templates and Views

TODO

##Assets

TODO