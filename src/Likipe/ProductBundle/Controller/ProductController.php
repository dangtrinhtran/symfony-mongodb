<?php

namespace Likipe\ProductBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Likipe\ProductBundle\Form\ProductType;
use Likipe\ProductBundle\Document\Product;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

class ProductController extends Controller {
	
	/**
	 * indexAction
	 * @author Rony <rony@likipe.se>
	 */
	public function indexAction() {

		$aAllProducts = $this->get('doctrine_mongodb')
				->getRepository('LikipeProductBundle:Product')
				->findAll();
		
		if (0 === count($aAllProducts)) {
			$this->get('session')
					->getFlashBag()
					->add('product_does_not_exist', $this->get('translator')
							->trans('Product does not exist!'));

			return $this->render('LikipeProductBundle:Default:default.html.twig');
		}

		
		return $this->render('LikipeProductBundle:Product:index.html.twig', array(
					'aProducts' => $aAllProducts
						)
		);
	}
	
	/**
	 * addAction
	 * Create product
	 * @author Rony <rony@likipe.se>
	 * @param \Symfony\Component\HttpFoundation\Request $request
	 */
	public function addAction(Request $request) {
		$oProduct = new Product();
		$form = $this->createForm(
				new ProductType(), $oProduct);
		/**
		 * Form for symfony3
		 */
		$form->handleRequest($request);
		if ($form->isValid()) {
			
			$dm = $this->get('doctrine_mongodb')->getManager();
			/**
			 * Persist: temporary variable to save, not save to database
			 */
			$dm->persist($oProduct);
			$dm->flush();

			$this->get('session')->getFlashBag()->add('product_success', $this->get('translator')->trans('Create successfully product: ' . $oProduct->getName()));

			return $this->redirect($this->generateUrl('LikipeProductBundle_Product_index'));
		}

		return $this->render('LikipeProductBundle:Product:add.html.twig', array(
					'form' => $form->createView()
		));
	}
	
	/**
	 * Get rid of the __DIR__ so it doesn't screw up
	 * When displaying uploaded doc/image in the view.
	 * @author Rony <rony@likipe.se>
	 * @return string
	 */
	protected function getUploadDir() {
		return 'uploads/products/';
	}

	/**
	 * Get directory path when upload.
	 * @author Rony <rony@likipe.se>
	 * @return string
	 */
	protected function getUploadRoot() {
		return __DIR__ . '/../../../../web/';
	}

	/**
	 * The absolute directory path where uploaded.
	 * Documents should be saved.
	 * @author Rony <rony@likipe.se>
	 * @return string
	 */
	protected function getUploadRootDir() {
		return __DIR__ . '/../../../../web/' . $this->getUploadDir();
	}
	
	public function uploadAjaxAction() {
		#$data = json_decode($request->getContent());
		if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $aFileUpload = isset($_FILES['filesUpload']) ? $_FILES['filesUpload'] : null;
			
			if (empty($aFileUpload)) {
				return new Response(json_encode(array(
					'error' => 'File upload empty!'
						), JSON_PRETTY_PRINT), 400, array('Content-Type' => 'application/json'));
			}
			
			if (!is_array($aFileUpload)) {
				return new Response(json_encode(array(
					'error' => 'File upload in request body must be an array.'
						), JSON_PRETTY_PRINT), 400, array('Content-Type' => 'application/json'));
			}
			
			if ($aFileUpload['error'] > 0) {
				return new Response(json_encode(array(
				'error' => 'File upload error.'
					), JSON_PRETTY_PRINT), 400, array('Content-Type' => 'application/json'));
			} else {
				$valid_file = TRUE;
				if ($aFileUpload['size'] > (1024000)) { //can't be larger than 1 MB
					$valid_file = FALSE;
					return new Response(json_encode(array(
						'error' => "File upload can't be larger than 1 MB."
							), JSON_PRETTY_PRINT), 400, array('Content-Type' => 'application/json'));
				}
				if ($valid_file) {
					$fileName = pathinfo($aFileUpload['name']);

					$fileUpload = $fileName['filename'] . '-' . time('now') . '.' . $fileName['extension'];
					move_uploaded_file($aFileUpload['tmp_name'], $this->getUploadRootDir() . $fileUpload);
					$dataResponse = array(
						'filename' => $fileUpload,
						'url' => $this->getUploadDir() . $fileUpload,
						'filesize' => $aFileUpload['size'],
						'extension' => $aFileUpload['type']
					);
					$aFileUpload = NULL;
				}
			}
        }
		return new Response(json_encode($dataResponse, JSON_PRETTY_PRINT), 200, array('Content-Type' => 'application/json'));
	}
}
