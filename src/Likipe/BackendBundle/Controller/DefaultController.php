<?php

namespace Likipe\BackendBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller {

	public function indexAction() {
		return $this->render('LikipeBackendBundle:Default:index.html.twig');
	}

	public function homeAction() {
		return $this->render('LikipeBackendBundle:Default:home.html.twig');
	}
	
	public function frontendAction() {
		return $this->render('LikipeBackendBundle:Default:frontend.html.twig');
	}
}
