<?php

namespace Likipe\FrontendBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
	public function defaultAction() {
		return $this->render('LikipeFrontendBundle:Default:default.html.twig');
	}
	
    public function indexAction() {
		return $this->render('LikipeFrontendBundle:Default:index.html.twig');
	}
}
