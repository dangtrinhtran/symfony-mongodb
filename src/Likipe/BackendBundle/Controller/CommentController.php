<?php

namespace Likipe\BackendBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Likipe\BackendBundle\Form\CommentType;
use Likipe\BackendBundle\Document\Comment;
use Symfony\Component\HttpFoundation\Request;

class CommentController extends Controller {
	
	public function indexAction($iPostId) {
		
		$aAllComments = $this->get('doctrine_mongodb')
				->getRepository('LikipeBackendBundle:Post')
				->getActiveComments($iPostId);

		if (0 === count($aAllComments)) {
			$this->get('session')
					->getFlashBag()
					->add('comment_does_not_exist', $this->get('translator')
							->trans('Comment does not exist!'));

			return $this->render('LikipeBackendBundle:Default:default.html.twig');
		}


		return $this->render('LikipeBackendBundle:Comment:index.html.twig', array(
					'aComments' => $aAllComments,
					'iPostId'	=> $iPostId
						)
		);
	}
	
	public function addAction(Request $request, $iPostId) {
		
		$oPost = $this->get('doctrine_mongodb')
				->getRepository('LikipeBackendBundle:Post')
				->find($iPostId);
		
		if (empty($oPost)) {
			throw $this->createNotFoundException( 'No found post by id '
    				. $iPostId . '!' );
		}
		
		$oComment = new Comment();
		$form = $this->createForm(
				new CommentType(), $oComment
		);
		/**
		 * Form for symfony3
		 */
		$form->handleRequest($request);
		if ($form->isValid()) {
			$dm = $this->get('doctrine_mongodb')->getManager();
			
			$oPost->addComment($oComment);
			
			$dm->persist($oComment);
			$dm->flush();
			$this->get('session')->getFlashBag()->add('comment_success', $this->get('translator')->trans('Add successfully comment for ' . $oPost->getTitle() . ' : ' . $oComment->getName()));

			return $this->redirect($this->generateUrl('LikipeBackendBundle_Post_index'));
		}

		return $this->render('LikipeBackendBundle:Comment:add.html.twig', array(
					'form' => $form->createView()
		));
	}
	
	public function deleteAction($iPostId, $iCommentId) {

		$dm = $this->get('doctrine_mongodb')->getManager();
		$oPost = $dm->getRepository('LikipeBackendBundle:Post')->find($iPostId);

		if (!$oPost) {
			throw $this->createNotFoundException(
					'No found post by id ' . $iPostId
			);
		}
		
		$oComment = $dm->getRepository('LikipeBackendBundle:Post')->getCommentById($iPostId, $iCommentId);

		$oPost->removeComment($oComment);
		
		$dm->flush();
		$this->get('session')->getFlashBag()->add('comment_success', $this->get('translator')->trans('Delete successfully comment: ' . $oComment->getName()));

		return $this->redirect($this->generateUrl('LikipeBackendBundle_Comment_index'));
	}
}
