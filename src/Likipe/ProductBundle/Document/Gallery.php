<?php

// src/Likipe/ProductBundle/Document/Gallery.php

namespace Likipe\ProductBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

/**
 * @MongoDB\EmbeddedDocument
 */
class Gallery {

	/**
	 * @MongoDB\Id(strategy="auto")
	 */
	protected $id;

	/**
	 * @MongoDB\String
	 */
	protected $name;

	/**
	 * @MongoDB\String
	 */
	protected $url;
	
	/**
	 * @MongoDB\String
	 */
	protected $file_size;

	/**
	 * @MongoDB\String
	 */
	protected $extension;

	/**
	 * Get id
	 *
	 * @return id $id
	 */
	public function getId() {
		return $this->id;
	}

	/**
	 * Set name
	 *
	 * @param string $name
	 * @return self
	 */
	public function setName($name) {
		$this->name = $name;
		return $this;
	}

	/**
	 * Get name
	 *
	 * @return string $name
	 */
	public function getName() {
		return $this->name;
	}

	/**
	 * Set url
	 *
	 * @param string $url
	 * @return self
	 */
	public function setUrl($url) {
		$this->url = $url;
		return $this;
	}

	/**
	 * Get url
	 *
	 * @return string $url
	 */
	public function getUrl() {
		return $this->url;
	}

	/**
	 * Set extension
	 *
	 * @param string $extension
	 * @return self
	 */
	public function setExtension($extension) {
		$this->extension = $extension;
		return $this;
	}

	/**
	 * Get extension
	 *
	 * @return string $extension
	 */
	public function getExtension() {
		return $this->extension;
	}

    /**
	 * Set file_size
	 *
	 * @param string $fileSize
	 * @return self
	 */
	public function setFileSize($fileSize) {
		$this->file_size = $fileSize;
		return $this;
	}

	/**
	 * Get file_size
	 *
	 * @return string $fileSize
	 */
	public function getFileSize() {
		return $this->file_size;
	}
}
