<?php

namespace Likipe\BackendBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Likipe\BackendBundle\Form\BlogType;
use Likipe\BackendBundle\Document\Blog;
use Symfony\Component\HttpFoundation\Request;

class BlogController extends Controller {

	/**
	 * indexAction
	 * @author Rony <rony@likipe.se>
	 */
	public function indexAction() {

		$aAllBlogs = $this->get('doctrine_mongodb')
				->getRepository('LikipeBackendBundle:Blog')
				->getActiveBlogs();
		
		if (0 === count($aAllBlogs)) {
			$this->get('session')
					->getFlashBag()
					->add('blog_does_not_exist', $this->get('translator')
							->trans('Blog does not exist!'));

			return $this->render('LikipeBackendBundle:Default:default.html.twig');
		}

		
		return $this->render('LikipeBackendBundle:Blog:index.html.twig', array(
					'aBlogs' => $aAllBlogs
						)
		);
	}

	/**
	 * addAction
	 * Create blog
	 * @author Rony <rony@likipe.se>
	 * @param type \Symfony\Component\HttpFoundation\Request $request
	 */
	public function addAction(Request $request) {

		$oBlog = new Blog();
		$form = $this->createForm(
				new BlogType(), $oBlog
		);
		/**
		 * Form for symfony3
		 */
		$form->handleRequest($request);
		if ($form->isValid()) {
			$dm = $this->get('doctrine_mongodb')->getManager();
			
			$dm->persist($oBlog);
			$dm->flush();
			$this->get('session')->getFlashBag()->add('blog_success', $this->get('translator')->trans('Create successfully blog: ' . $oBlog->getTitle()));

			return $this->redirect($this->generateUrl('LikipeBackendBundle_Blog_index'));
		}

		return $this->render('LikipeBackendBundle:Blog:add.html.twig', array(
					'form' => $form->createView()
		));
	}

	/**
	 * editAction
	 * Edit blog
	 * @author Rony <rony@likipe.se>
	 * @param type \Symfony\Component\HttpFoundation\Request $request, $iBlogId
	 */
	public function editAction(Request $request, $iBlogId) {

		$dm = $this->get('doctrine_mongodb')->getManager();
		$oBlog = $dm->getRepository('LikipeBackendBundle:Blog')->find($iBlogId);

		if (!$oBlog) {
			throw $this->createNotFoundException(
					'No blog found for id ' . $iBlogId
			);
		}

		$form = $this->createForm(
				new BlogType(), $oBlog
		);
		/**
		 * Form for symfony3
		 */
		$form->handleRequest($request);
		if ($form->isValid()) {
			$dm->flush();
			$this->get('session')->getFlashBag()->add('blog_success', $this->get('translator')->trans('Edit successfully blog: ' . $oBlog->getTitle()));

			return $this->redirect($this->generateUrl('LikipeBackendBundle_Blog_index'));
		}

		return $this->render('LikipeBackendBundle:Blog:edit.html.twig', array(
					'form' => $form->createView(),
					'iBlogId' => $iBlogId
		));
	}

	/**
	 * deleteAction
	 * Delete blog
	 * @author Rony <rony@likipe.se>
	 * @param type $iBlogId
	 */
	public function deleteAction($iBlogId) {

		$dm = $this->get('doctrine_mongodb')->getManager();
		$oBlog = $dm->getRepository('LikipeBackendBundle:Blog')->find($iBlogId);

		if (!$oBlog) {
			throw $this->createNotFoundException(
					'No blog found for id ' . $iBlogId
			);
		}

		$oBlog->setDelete(TRUE);
		
		$dm->flush();
		$this->get('session')->getFlashBag()->add('blog_success', $this->get('translator')->trans('Delete successfully blog: ' . $oBlog->getTitle()));

		return $this->redirect($this->generateUrl('LikipeBackendBundle_Blog_index'));
	}

}
