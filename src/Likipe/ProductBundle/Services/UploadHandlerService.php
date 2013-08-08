<?php
namespace Likipe\ProductBundle\Services;

use Symfony\Component\HttpFoundation\Response;

class UploadHandlerService {

	private $options;
	
	function __construct($options) {
		$options = array(
			'upload_dir' => $this->getUploadRootDir(),
			'upload_url' => $this->getUploadDir(),
			'param_name' => 'filesUpload',
			// Set the following option to 'POST', if your server does not support
			// DELETE requests. This is a parameter sent to the client:
			'delete_type' => 'DELETE',
			'error_empty'	=> 'File upload empty!',
			'error_array'	=> 'File upload in request body must be an array.',
			'file_error'	=> 'File upload error.',
			'error_size'	=> "File upload can't be larger than 1 MB."
		);
		$this->options = $options;
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
	
	public function uploadAjax() {
		if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] === 'POST') {
			$aFileUpload = isset($_FILES[$this->options['param_name']]) ? 
					$_FILES[$this->options['param_name']] : null;

			if (empty($aFileUpload)) {
				return new Response(json_encode(array(
							'error' => $this->options['error_empty']
								), JSON_PRETTY_PRINT), 400, array('Content-Type' => 'application/json'));
			}

			if (!is_array($aFileUpload)) {
				return new Response(json_encode(array(
							'error' => $this->options['error_array']
								), JSON_PRETTY_PRINT), 400, array('Content-Type' => 'application/json'));
			}

			if ($aFileUpload['error'] > 0) {
				return new Response(json_encode(array(
							'error' => $this->options['file_error']
								), JSON_PRETTY_PRINT), 400, array('Content-Type' => 'application/json'));
			} else {
				$validFile = TRUE;
				if ($aFileUpload['size'] > (1024000)) { //can't be larger than 1 MB
					$validFile = FALSE;
					return new Response(json_encode(array(
								'error' => $this->options['error_size']
									), JSON_PRETTY_PRINT), 400, array('Content-Type' => 'application/json'));
				}
				if ($validFile) {
					$fileName = pathinfo($aFileUpload['name']);

					$fileUpload = $fileName['filename'] . '-' . time('now') . '.' . $fileName['extension'];
					move_uploaded_file($aFileUpload['tmp_name'], $this->options['upload_dir'] . $fileUpload);
					$dataResponse = array(
						'filename' => $fileUpload,
						'url' => $this->options['upload_url'] . $fileUpload,
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
