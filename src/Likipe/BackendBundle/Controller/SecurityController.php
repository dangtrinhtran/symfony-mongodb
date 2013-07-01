<?php

namespace Likipe\BackendBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\SecurityContext;

class SecurityController extends Controller {

	public function loginAction() {
		$request = $this->getRequest();
		$session = $request->getSession();

		// get the login error if there is one
		if ($request->attributes->has(SecurityContext::AUTHENTICATION_ERROR)) {
			$error = $request->attributes->get(
					SecurityContext::AUTHENTICATION_ERROR
			);
		} else {
			$error = $session->get(SecurityContext::AUTHENTICATION_ERROR);
			$session->remove(SecurityContext::AUTHENTICATION_ERROR);
		}
		$sLastUsername = (null === $session) ? '' : $session->get(SecurityContext::LAST_USERNAME);

		return $this->render(
						'LikipeBackendBundle:Security:login.html.twig', array(
					// last username entered by the user
					'last_username' => $sLastUsername,
					'error' => $error,
		));
	}

	public function logoutAction() {
		throw new \RuntimeException('You must activate the logout in your security firewall configuration.');
	}

}
