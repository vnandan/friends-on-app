<?php

/**
 *	Represents a user
 */
class UserData {

	private $name;
	private $imageUrl;

	public function __construct() {
		$this->name = 'Default';
		$this->imageUrl = '';
	}

	/**
	 *	@param string $name
	 *	@return UserData
	 */
	public function setName($name) {
		$this->name = $name;

		return $this;
	}

	/**
	 *	@return string
	 */
	public function getName() {
		return $this->name;
	}

	/**
	 *	@param string $url
	 *	@return UserData
	 */
	public function setImageUrl($url) {
		$this->imageUrl = $url;

		return $this;
	}

	/**
	 *	@return string
	 */
	public function getImageUrl() {
		return $this->imageUrl;
	}
}

