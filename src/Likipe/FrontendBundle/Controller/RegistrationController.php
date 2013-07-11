<?php

namespace Likipe\FrontendBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class RegistrationController extends Controller {

	public function registerAction(Request $request) {
		
		$formFactory = $this->container->get('fos_user.registration.form.factory');
        $userManager = $this->container->get('fos_user.user_manager');

        $user = $userManager->createUser();
        $user->setEnabled(true);
        $user->setUsername(time());

        $form = $formFactory->createForm();
        $form->setData($user);
		
		$form->handleRequest($request);
		if ($form->isValid()) {

			$dm = $this->get('doctrine_mongodb')->getManager();
			$dm->persist($user);
			$dm->flush();
			
			return $this->redirect($this->generateUrl('Likipe_Frontend'));
		}
		
		return $this->container->get('templating')->renderResponse('FOSUserBundle:Registration:register.html.twig', array(
					'form' => $form->createView(),
		));
	}

}
