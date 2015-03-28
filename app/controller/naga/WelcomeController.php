<?php

namespace App\Controller\Naga;

use Naga\Core\Controller\Controller;
use Naga\Core\View\View;

final class WelcomeController extends Controller
{
	public function getWelcome($params)
	{
		$view = View::htmlTwigView();
		$view->execute('naga/welcome/welcome.twig');
	}
}