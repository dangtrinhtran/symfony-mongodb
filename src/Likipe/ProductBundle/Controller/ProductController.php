<?php

namespace Likipe\ProductBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Likipe\ProductBundle\Form\ProductType;
use Likipe\ProductBundle\Document\Product;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

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
			$aUploadImage = $request->get('urlImage');
			$oProduct->setGallery($aUploadImage);
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
	 * editAction
	 * Edit product
	 * @author Rony <rony@likipe.se>
	 * @param type \Symfony\Component\HttpFoundation\Request $request, $iProductId
	 */
	public function editAction(Request $request, $iProductId) {

		$dm = $this->get('doctrine_mongodb')->getManager();
		$oProduct = $dm->getRepository('LikipeProductBundle:Product')->find($iProductId);

		if (!$oProduct) {
			throw $this->createNotFoundException(
					'No product found for id ' . $iProductId
			);
		}
		$form = $this->createForm(
				new ProductType(), $oProduct
		);
		/**
		 * Form for symfony3
		 */
		$form->handleRequest($request);
		if ($form->isValid()) {
			$aUploadImage = $request->get('urlImage');
			$oProduct->setGallery($aUploadImage);
			
			$dm = $this->get('doctrine_mongodb')->getManager();
			$dm->flush();

			$this->get('session')->getFlashBag()->add('product_success', $this->get('translator')->trans('Edit successfully product: ' . $oProduct->getName()));

			return $this->redirect($this->generateUrl('LikipeProductBundle_Product_index'));
		}
		
		return $this->render('LikipeProductBundle:Product:edit.html.twig', array(
					'form' => $form->createView(),
					'iProductId' => $iProductId
		));
	}
	
	public function getGallaryAjaxAction($iProductId) {
		
		$dm = $this->get('doctrine_mongodb')->getManager();
		$oProduct = $dm->getRepository('LikipeProductBundle:Product')->find($iProductId);

		if (!$oProduct) {
			return new Response(json_encode(array(
					'error' => sprintf('No product found for id %d.', $iProductId)
						), JSON_PRETTY_PRINT), 200, array('Content-Type' => 'application/json'));
		}
		$aGalleries = $oProduct->getGalleries();
		$response = array();
		if (!empty($aGalleries)) {
			foreach ($aGalleries as $oGallery) {
				$response[] = array(
					'filename' => $oGallery->getName(),
					'url' => $oGallery->getUrl(),
					'filesize' => $oGallery->getFileSize(),
					'extension' => $oGallery->getExtension()
				);
			}
		}
		return new Response(json_encode($response, JSON_PRETTY_PRINT), 200, array('Content-Type' => 'application/json'));
	}

	public function uploadAjaxAction() {
		return $this->get("likipe.uploadfile.service")->uploadAjax();
	}
	
	public function deleteAjaxAction(Request $request) {
		return $this->get("likipe.uploadfile.service")->removeAjax($request);
	}
}
