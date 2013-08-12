<?php

// src/Likipe/ProductBundle/Document/Product.php

namespace Likipe\ProductBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Likipe\ProductBundle\Document\Gallery;

/**
 * @MongoDB\Document(collection="Likipe_Product", repositoryClass="Likipe\ProductBundle\Repository\ProductRepository")
 */
class Product {

	/**
	 * @MongoDB\Id(strategy="auto")
	 */
	protected $id;

	/**
	 * @MongoDB\String
	 */
	protected $name;

	/**
	 * @MongoDB\Float
	 */
	protected $price;

	/**
	 * @MongoDB\Date
	 */
	protected $created;

	/**
	 * @MongoDB\Date
	 */
	protected $updated;

	/**
	 * @MongoDB\Boolean
	 */
	public $active;
	
	/**
     * @MongoDB\EmbedMany(targetDocument="Likipe\ProductBundle\Document\Gallery")
     */
	protected $galleries = array();

	public function __construct() {
		$this->galleries = new \Doctrine\Common\Collections\ArrayCollection();
		$this->active = TRUE;
	}
	
	/** 
	 * @MongoDB\PrePersist
	 */
	public function prePersist() {
		$this->setCreated(new \DateTime('now'));
		$this->setUpdated(new \DateTime('now'));
	}

	/** 
	 * @MongoDB\PreUpdate
	 */
	public function preUpdate() {
		$this->setUpdated(new \DateTime('now'));
	}

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
	 * Set price
	 *
	 * @param float $price
	 * @return self
	 */
	public function setPrice($price) {
		$this->price = $price;
		return $this;
	}

	/**
	 * Get price
	 *
	 * @return float $price
	 */
	public function getPrice() {
		return $this->price;
	}

	/**
	 * Set created
	 *
	 * @param date $created
	 * @return self
	 */
	public function setCreated($created) {
		$this->created = $created;
		return $this;
	}

	/**
	 * Get created
	 *
	 * @return date $created
	 */
	public function getCreated() {
		return $this->created;
	}

	/**
	 * Set updated
	 *
	 * @param date $updated
	 * @return self
	 */
	public function setUpdated($updated) {
		$this->updated = $updated;
		return $this;
	}

	/**
	 * Get updated
	 *
	 * @return date $updated
	 */
	public function getUpdated() {
		return $this->updated;
	}

	/**
	 * Set active
	 *
	 * @param boolean $active
	 * @return self
	 */
	public function setActive($active) {
		$this->active = $active;
		return $this;
	}

	/**
	 * Get active
	 *
	 * @return boolean $active
	 */
	public function getActive() {
		return $this->active;
	}


    /**
	 * Add galleries
	 *
	 * @param Likipe\ProductBundle\Document\Gallery $galleries
	 */
	public function addGallerie(\Likipe\ProductBundle\Document\Gallery $galleries) {
		$this->galleries[] = $galleries;
	}
	
	public function setGallery(array $aUploadImage = NULL) {
		
		$galleries = array();
		if (!empty($aUploadImage)) {
			foreach ($aUploadImage as $size => $url) {
				$fileName = pathinfo($url);
				$gallery = new Gallery();
				$gallery->setName($fileName['basename']);
				$gallery->setUrl($url);
				$gallery->setFileSize($size);
				$gallery->setExtension($fileName['extension']);
				$galleries[] = $gallery;
			}
		}
		$this->galleries = $galleries;
	}

	/**
	 * Remove galleries
	 *
	 * @param Likipe\ProductBundle\Document\Gallery $galleries
	 */
	public function removeGallerie(\Likipe\ProductBundle\Document\Gallery $galleries) {
		$this->galleries->removeElement($galleries);
	}

	/**
	 * Get galleries
	 *
	 * @return Doctrine\Common\Collections\Collection $galleries
	 */
	public function getGalleries() {
		return $this->galleries;
	}
}
