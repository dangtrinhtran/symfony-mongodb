<?php

namespace Likipe\BackendBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller {

	public function indexAction() {
		return $this->render('LikipeBackendBundle:Default:index.html.twig');
	}
}
