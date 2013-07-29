<?php

namespace Likipe\BackendBundle\Services;


class UploadFileService {

	/**
	 * Get rid of the __DIR__ so it doesn't screw up
	 * When displaying uploaded doc/image in the view.
	 * @author Rony <rony@likipe.se>
	 * @return string
	 */
	protected function getUploadDir() {
		return 'uploads/posts/';
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
	
	
	/**
	 * @author Rony <rony@likipe.se>
	 * @param array $_FILES, name of Field
	 * @return string fileUpload
	 */
	public function uploadFile($aFile = array(), $sKey = 'featuredimage' ) {
		// the file property can be empty if the field is not required
		if (empty($aFile)) {
			return;
		}
		
		foreach ($aFile as $aFiles) {
			if($aFiles['error'][$sKey] > 0) {
				$response = 'File error!';
				return;
			} else {
				$valid_file = TRUE;
				if ($aFiles['size'][$sKey] > (1024000)) { //can't be larger than 1 MB
					$valid_file = FALSE;
					$response = "File error! Can't be larger than 1 MB";
					return;
				}
				if ($valid_file) {
					$fileName = pathinfo($aFiles['name'][$sKey]);
					
					$fileUpload = $fileName['filename'] . '-' . time('now') . '.' . $fileName['extension'];
					move_uploaded_file($aFiles['tmp_name'][$sKey], $this->getUploadRootDir() . $fileUpload);
					$aFile = NULL;
					$response = "Successfully!";
					return $this->getUploadDir() . $fileUpload;
				}
			}
		}
		$array['response'] = $response;
		echo json_encode($array);
	}
	
	
	/**
	 * @author Rony <rony@likipe.se>
	 * Remove file upload.
	 * @param link featuredimage
	 * @return bool <b>TRUE</b> on success or <b>FALSE</b> on failure.
	 */
	public function removeUploadFile($featuredimage) {
		if (empty($featuredimage))
			return;
		$file = $this->getUploadRoot() . $featuredimage;

		if ($file) {
			if (file_exists($file) && is_writable($file))
				return unlink($file);
		}
	}
	
}
