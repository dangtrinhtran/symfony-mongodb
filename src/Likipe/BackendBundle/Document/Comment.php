<?php

// src/Likipe/BackendBundle/Document/Comment.php

namespace Likipe\BackendBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @MongoDB\EmbeddedDocument
 */
class Comment {

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
	 * @Assert\NotBlank()
	 * @Assert\Email()
	 */
	protected $email;

	/**
	 * @MongoDB\String
	 */
	protected $content;

	/**
	 * @MongoDB\Date
	 */
	protected $created;

	/**
	 * @MongoDB\Boolean
	 */
	protected $isActive;

	public function __construct() {
		$this->isActive = FALSE;
	}

	/**
	 * @MongoDB\prePersist
	 */
	public function prePersist() {
		$this->setCreated(new \DateTime('now'));
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
	 * Set email
	 *
	 * @param string $email
	 * @return self
	 */
	public function setEmail($email) {
		$this->email = $email;
		return $this;
	}

	/**
	 * Get email
	 *
	 * @return string $email
	 */
	public function getEmail() {
		return $this->email;
	}

	/**
	 * Set content
	 *
	 * @param string $content
	 * @return self
	 */
	public function setContent($content) {
		$this->content = $content;
		return $this;
	}

	/**
	 * Get content
	 *
	 * @return string $content
	 */
	public function getContent() {
		return $this->content;
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
	 * Set isActive
	 *
	 * @param boolean $isActive
	 * @return self
	 */
	public function setIsActive($isActive) {
		$this->isActive = $isActive;
		return $this;
	}

	/**
	 * Get isActive
	 *
	 * @return boolean $isActive
	 */
	public function getIsActive() {
		return $this->isActive;
	}
}
