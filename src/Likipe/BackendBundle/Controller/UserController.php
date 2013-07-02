<?php

namespace Likipe\BackendBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Likipe\BackendBundle\Form\UserType;
use Likipe\BackendBundle\Document\User;
use Symfony\Component\HttpFoundation\Request;

class UserController extends Controller {

	public function indexAction() {

		$oAllUsers = $this->get('doctrine_mongodb')
				->getRepository('LikipeBackendBundle:User')
				->findAll();

		if (0 === count($oAllUsers)) {
			$this->get('session')
					->getFlashBag()
					->add('user_does_not_exist', $this->get('translator')->trans('User does not exist!'));

			return $this->render('LikipeBackendBundle:Default:default.html.twig');
		}

		return $this->render('LikipeBackendBundle:User:index.html.twig', array(
					'oUsers' => $oAllUsers
						)
		);
	}

	public function deniedAction() {
		return $this->render('LikipeBackendBundle:Default:denied.html.twig');
	}

	public function addAction(Request $request) {

		$oUser = new User();
		$form = $this->createForm(
				new UserType(), $oUser
		);
		/**
		 * Form for symfony3
		 */
		$form->handleRequest($request);
		if ($form->isValid()) {

			$dm = $this->get('doctrine_mongodb')->getManager();
			$dm->persist($oUser);
			$dm->flush();
			$this->get('session')->getFlashBag()->add('user_success', $this->get('translator')->trans('Create successfully user: ' . $oUser->getUsername()));

			return $this->redirect($this->generateUrl('LikipeBackendBundle_User_index'));
		}

		return $this->render('LikipeBackendBundle:User:add.html.twig', array(
					'user' => $form->createView()
		));
	}

}
