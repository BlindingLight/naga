<?php

namespace App;

use Naga\Core\Action\Action;
use Naga\Core\Application;

class App extends Application
{
	public function run()
	{
		// logout
		if ($this->auth()->isLoggedIn() && $this->input()->exists('logout'))
		{
			$this->auth()->logout();
			$this->redirect();
		}

		$defaultRoute = self::auth()->isLoggedIn()
						? self::config()->application->get('defaultRouteIfLoggedIn')
						: self::config()->application->get('defaultRoute');
		self::router()->setDefaultRoute($defaultRoute);
		$controllerResult = self::router()->routeUri();
		if ($controllerResult instanceof Action)
			$controllerResult->execute();
		else if (is_string($controllerResult))
			echo $controllerResult;
	}
}