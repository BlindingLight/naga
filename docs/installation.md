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