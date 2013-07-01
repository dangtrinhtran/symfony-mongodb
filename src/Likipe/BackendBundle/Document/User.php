<?php

// src/Likipe/BackendBundle/Document/User.php

namespace Likipe\BackendBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @MongoDB\Document(collection="Likipe_User", repositoryClass="Likipe\BackendBundle\Repository\UserRepository")
 */
class User implements UserInterface {

	/**
	 * @MongoDB\Id(strategy="auto")
	 */
	private $id;

	/**
	 * @MongoDB\String
	 */
	private $firstname;

	/**
	 * @MongoDB\String
	 */
	private $lastname;

	/**
	 * @MongoDB\String
	 * @Assert\NotBlank()
	 */
	private $username;

	/**
	 * @MongoDB\String
	 */
	private $salt;

	/**
	 * @MongoDB\String
	 * @Assert\NotBlank()
	 */
	private $password;

	/**
	 * @MongoDB\String
	 */
	private $role;

	/**
	 * @MongoDB\String
	 * @Assert\NotBlank()
	 */
	private $email;

	/**
	 * @MongoDB\Boolean
	 */
	private $isActive;

	public function __construct() {
		$this->isActive = true;
		$this->salt = md5(uniqid(null, true));
	}

	/**
	 * @inheritDoc
	 */
	public function getUsername() {
		return $this->username;
	}

	/**
	 * @inheritDoc
	 */
	public function getSalt() {
		return $this->salt;
	}

	/**
	 * @inheritDoc
	 */
	public function getPassword() {
		return $this->password;
	}

	/**
	 * @inheritDoc
	 */
	public function getRoles() {
		return array('ROLE_USER');
	}

	/**
	 * @inheritDoc
	 */
	public function eraseCredentials() {
		
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
	 * Set firstname
	 *
	 * @param string $firstname
	 * @return self
	 */
	public function setFirstname($firstname) {
		$this->firstname = $firstname;
		return $this;
	}

	/**
	 * Get firstname
	 *
	 * @return string $firstname
	 */
	public function getFirstname() {
		return $this->firstname;
	}

	/**
	 * Set lastname
	 *
	 * @param string $lastname
	 * @return self
	 */
	public function setLastname($lastname) {
		$this->lastname = $lastname;
		return $this;
	}

	/**
	 * Get lastname
	 *
	 * @return string $lastname
	 */
	public function getLastname() {
		return $this->lastname;
	}

	/**
	 * Set username
	 *
	 * @param string $username
	 * @return self
	 */
	public function setUsername($username) {
		$this->username = $username;
		return $this;
	}

	/**
	 * Set salt
	 *
	 * @param string $salt
	 * @return self
	 */
	public function setSalt($salt) {
		$this->salt = $salt;
		return $this;
	}

	/**
	 * Set password
	 *
	 * @param string $password
	 * @return self
	 */
	public function setPassword($password) {
		$this->password = $password;
		return $this;
	}

	/**
	 * Set role
	 *
	 * @param string $role
	 * @return self
	 */
	public function setRole($role) {
		$this->role = $role;
		return $this;
	}

	/**
	 * Get role
	 *
	 * @return string $role
	 */
	public function getRole() {
		return $this->role;
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
