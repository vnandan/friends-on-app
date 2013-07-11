<?php

class OauthRequestToken {

	private $token;
	private $tokenSecret;
	private $callbackConfirmed;

	/**
	 *	@param string $token
	 *	@return OauthRequestToken
	 */
	public function setToken($token) {
		$this->token = $token;

		return $this;
	}

	/**
	 *	@return string
	 */
	public function getToken() {
		return $this->token;
	}

	/**
	 *	@param string $secret
	 *	@return OauthRequestToken
	 */
	public function setTokenSecret($secret) {
		$this->tokenSecret = $secret;

		return $this;
	}

	/**
	 *	@return string
	 */
	public function getTokenSecret() {
		return $this->tokenSecret;
	}

	/**
	 *	@param boolean $confirmed
	 *	@return OauthRequestToken
	 */
	public function setCallbackConfirmed($confirmed) {
		$this->callbackConfirmed = $confirmed;

		return $this;
	}

	/**
	 *	@return boolean
	 */
	public function getCallbackConfirmed() {
		return $this->callbackConfirmed;
	}
}

