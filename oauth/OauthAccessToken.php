<?php

/**
 *	Represents an OAUTH access token
 */
class OauthAccessToken {

	private $token;
	private $refreshToken;

	/**
	 *	@param string $token
	 *	@return OauthAccessToken
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
	 *	@param string $refresh_token
	 *	@return OauthAccessToken
	 */
	public function setRefreshToken($refresh_token) {
		$this->refreshToken = $refresh_token;

		return $this;
	}

	/**
	 *	@return string
	 */
	public function getRefreshToken() {
		return $this->refreshToken;
	}
}

