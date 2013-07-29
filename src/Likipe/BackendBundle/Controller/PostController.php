<?php

namespace Likipe\BackendBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Likipe\BackendBundle\Form\PostType;
use Likipe\BackendBundle\Document\Post;
use Symfony\Component\HttpFoundation\Request;

class PostController extends Controller {

	/**
	 * indexAction
	 * @author Rony <rony@likipe.se>
	 * @param $iPage
	 */
	public function indexAction($iPage) {
		$aAllPosts = $this->get('doctrine_mongodb')
				->getRepository('LikipeBackendBundle:Post')
				->findAll();
		
		if (0 === count($aAllPosts)) {
			$this->get('session')
					->getFlashBag()
					->add('post_does_not_exist', $this->get('translator')
							->trans('Post does not exist!'));

			return $this->render('LikipeBackendBundle:Default:default.html.twig');
		}
		$iTotalPosts = count($aAllPosts);
		$iPostsPerPage = $this->container->getParameter('max_post_on_post');
		
		$fLastPage = ceil($iTotalPosts / $iPostsPerPage);
		$iPreviousPage = $iPage > 1 ? $iPage - 1 : 1;
		$iNextPage = $iPage < $fLastPage ? $iPage + 1 : $fLastPage;

		$dm = $this->get('doctrine_mongodb')->getManager();
		$iOffset = $iPage * $iPostsPerPage - $iPostsPerPage;
		$aPostPagination = $dm->getRepository('LikipeBackendBundle:Post')
				->getActivePosts($iPostsPerPage, $iOffset);

		return $this->render('LikipeBackendBundle:Post:index.html.twig', array(
					'aPosts' => $aPostPagination,
					'fLastPage' => $fLastPage,
					'iPreviousPage' => $iPreviousPage,
					'iCurrentPage' => $iPage,
					'iNextPage' => $iNextPage,
					'iTotalPosts' => $iTotalPosts
						)
		);
	}

	/**
	 * addAction
	 * Create post
	 * @author Rony <rony@likipe.se>
	 * @param \Symfony\Component\HttpFoundation\Request $request
	 */
	public function addAction(Request $request) {
		$oPost = new Post();
		$securityContext = $this->container->get('security.context');
		$form = $this->createForm(
				new PostType($securityContext), $oPost);
		/**
		 * Form for symfony3
		 */
		if ($this->getRequest()->files->get('post[featuredimage]')) {
			echo 'trinh dmfdsfdkj';
			exit;
		}
		
		$form->handleRequest($request);
		if ($form->isValid()) {
			//Upload file
			$uploadfile_service = $this->get("likipe.backend.uploadfile");
			$featuredimage = $uploadfile_service->uploadFile($_FILES);
			$oPost->setFeaturedimage($featuredimage);
			
			$dm = $this->get('doctrine_mongodb')->getManager();
			/**
			 * Persist: temporary variable to save, not save to database
			 */
			$dm->persist($oPost);
			$dm->flush();

			$log_service = $this->get("likipe.backend.log");
			//$this->getUser();
			$message = $securityContext->getToken()->getUser()->getUsername() . ': Create successfully post: ' . $oPost->getTitle();
			$log_service->writeLog($message);

			$this->get('session')->getFlashBag()->add('post_success', $this->get('translator')->trans('Create successfully post: ' . $oPost->getTitle()));

			return $this->redirect($this->generateUrl('LikipeBackendBundle_Post_index'));
		}

		return $this->render('LikipeBackendBundle:Post:add.html.twig', array(
					'form' => $form->createView()
		));
	}

	/**
	 * editAction
	 * Edit post
	 * @author Rony <rony@likipe.se>
	 * @param \Symfony\Component\HttpFoundation\Request $request, $iPostId
	 */
	public function editAction(Request $request, $iPostId) {

		$dm = $this->get('doctrine_mongodb')->getManager();
		$oPost = $dm->getRepository('LikipeBackendBundle:Post')->find($iPostId);
		if (!$oPost) {
			throw $this->createNotFoundException(
					'No post found for id ' . $iPostId
			);
		}
		$featuredimageOld = $oPost->getFeaturedimage();
		$securityContext = $this->container->get('security.context');
		$form = $this->createForm(
				new PostType($securityContext), $oPost
		);
		/**
		 * Form for symfony3
		 */
		$form->handleRequest($request);
		if ($form->isValid()) {
			$oFile = $form->get('featuredimage')->getData();
			
			if (!empty($oFile)) {
				//Upload file
				$uploadfile_service = $this->get("likipe.backend.uploadfile");
				$featuredimage = $uploadfile_service->uploadFile($_FILES);
				$oPost->setFeaturedimage($featuredimage);
			} else
				$oPost->setFeaturedimage($featuredimageOld);
			
			$dm->flush();

			$log_service = $this->get("likipe.backend.log");
			$message = $securityContext->getToken()->getUser()->getUsername() . ': Edit successfully post: ' . $oPost->getTitle();
			$log_service->writeLog($message);

			$this->get('session')->getFlashBag()->add('post_success', $this->get('translator')->trans('Edit successfully post: ' . $oPost->getTitle()));

			return $this->redirect($this->generateUrl('LikipeBackendBundle_Post_index'));
		}

		return $this->render('LikipeBackendBundle:Post:edit.html.twig', array(
					'form' => $form->createView(),
					'oPost' => $oPost,
					'iPostId' => $iPostId
		));
	}

	/**
	 * deleteAction
	 * Delete post
	 * @author Rony <rony@likipe.se>
	 * @param type $iPostId
	 */
	public function deleteAction($iPostId) {

		$securityContext = $this->container->get('security.context');

		$dm = $this->get('doctrine_mongodb')->getManager();
		$oPost = $dm->getRepository('LikipeBackendBundle:Post')->find($iPostId);

		if (!$oPost) {
			throw $this->createNotFoundException(
					'No post found for id ' . $iPostId
			);
		}
		
		//Remove file
		$uploadfile_service = $this->get("likipe.backend.uploadfile");
		$uploadfile_service->removeUploadFile($oPost->getFeaturedimage());
		
		$dm->remove($oPost);
		$dm->flush();

		$log_service = $this->get("likipe.backend.log");
		$message = $securityContext->getToken()->getUser()->getUsername() . ': Delete successfully post: ' . $oPost->getTitle();
		$log_service->writeLog($message);

		$this->get('session')->getFlashBag()->add('post_success', $this->get('translator')->trans('Delete successfully post: ' . $oPost->getTitle()));
		return $this->redirect($this->generateUrl('LikipeBackendBundle_Post_index'));
	}

}
