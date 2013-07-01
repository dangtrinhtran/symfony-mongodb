<?php

// src/Likipe/BackendBundle/Document/Blog.php

namespace Likipe\BackendBundle\Document;


use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @MongoDB\Document(collection="Likipe_Blog", repositoryClass="Likipe\BackendBundle\Repository\BlogRepository")
 */
class Blog {

	/**
	 * @MongoDB\Id(strategy="auto")
	 */
	protected $id;

	/**
	 * @MongoDB\String
	 * @Assert\NotBlank()
	 */
	protected $title;

	/**
	 * @MongoDB\String
	 */
	protected $description;

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
	protected $delete;
	
	/** 
	 * @MongoDB\PrePersist
	 */
	public function prePersist() {
		$this->setCreated(new \DateTime('now'));
		$this->setUpdated(new \DateTime('now'));
		$this->setDelete(FALSE);
	}

	/** 
	 * @MongoDB\PreUpdate
	 */
	public function preUpdate() {
		$this->setUpdated(new \DateTime('now'));
	}

	/**
	 * @MongoDB\ReferenceMany(targetDocument="Likipe\BackendBundle\Document\Post", mappedBy="blog", cascade={"remove"})
	 */
	protected $posts = array();

	public function __construct() {
		$this->posts = new \Doctrine\Common\Collections\ArrayCollection();
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
	 * Set title
	 *
	 * @param string $title
	 * @return self
	 */
	public function setTitle($title) {
		$this->title = $title;
		return $this;
	}

	/**
	 * Get title
	 *
	 * @return string $title
	 */
	public function getTitle() {
		return $this->title;
	}

	/**
	 * Set description
	 *
	 * @param string $description
	 * @return self
	 */
	public function setDescription($description) {
		$this->description = $description;
		return $this;
	}

	/**
	 * Get description
	 *
	 * @return string $description
	 */
	public function getDescription() {
		return $this->description;
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
	 * Set delete
	 *
	 * @param boolean $delete
	 * @return self
	 */
	public function setDelete($delete) {
		$this->delete = $delete;
		return $this;
	}

	/**
	 * Get delete
	 *
	 * @return boolean $delete
	 */
	public function getDelete() {
		return $this->delete;
	}

	/**
	 * Add posts
	 *
	 * @param Likipe\BackendBundle\Document\Post $posts
	 */
	public function addPost(\Likipe\BackendBundle\Document\Post $posts) {
		$this->posts[] = $posts;
	}

	/**
	 * Remove posts
	 *
	 * @param Likipe\BackendBundle\Document\Post $posts
	 */
	public function removePost(\Likipe\BackendBundle\Document\Post $posts) {
		$this->posts->removeElement($posts);
	}

	/**
	 * Get posts
	 *
	 * @return Doctrine\Common\Collections\Collection $posts
	 */
	public function getPosts() {
		return $this->posts;
	}

}
