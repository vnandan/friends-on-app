<?php

require_once __DIR__ . '/OauthAccessToken.php';
require_once __DIR__ . '/OauthRequestToken.php';

/**
 *	This defines the basic functionality for
 *	any OAUTH provider
 */
abstract class OauthProvider {

	protected $urls;
	protected $scope;
	protected $ownerId;
	protected $ownerSecret;
	protected $redirectUri;
	protected $requestToken;
	protected $accessToken;

	protected function __construct() {
		$this->requestToken = null;
		$this->accessToken = null;
		$this->urls = array(
			'authorization_url' => '',
			'access_token_url' => ''
		);
		$this->scope = null;
	}

	/**
	 *	@param array $urls
	 *	@return OauthProvider
	 */
	protected function setUrls(array $urls) {
		$this->urls = $urls;

		return $this;
	}

	/**
	 *	@return array
	 */
	protected function getUrls() {
		return $this->urls;
	}

	/**
	 *	@param string $scope
	 *	@return OauthProvider
	 */
	protected function setScope($scope) {
		$this->scope = $scope;

		return $this;
	}

	/**
	 *	@return string
	 */
	protected function getScope() {
		return $this->scope;
	}

	/**
	 *	@param string $id
	 *	@return OauthProvider
	 */
	public function setOwnerId($id) {
		$this->ownerId = $id;

		return $this;
	}

	/**
	 *	@return string
	 */
	public function getOwnerId() {
		return $this->ownerId;
	}

	/**
	 *	@param string $secret
	 *	@return OauthProvider
	 */
	public function setOwnerSecret($secret) {
		$this->ownerSecret = $secret;

		return $this;
	}

	/**
	 *	@return string
	 */
	public function getOwnerSecret() {
		return $this->ownerSecret;
	}

	/**
	 *	@param string $uri
	 *	@return OauthProvider
	 */
	public function setRedirectUri($uri) {
		$this->redirectUri = $uri;

		return $this;
	}

	/**
	 *	@return string
	 */
	public function getRedirectUri() {
		return $this->redirectUri;
	}

	/**
	 *	@param OauthAccessToken $token
	 *	@return OauthProvider
	 */
	protected function setAccessToken(OauthAccessToken $token) {
		$this->accessToken = $token;

		return $this;
	}

	/**
	 *	$return OauthAccessToken
	 */
	protected function getAccessToken() {
		return $this->accessToken;
	}

	/**
	 *	@param OauthRequestToken $token
	 *	@return OauthProvider
	 */
	protected function setRequestToken(OauthRequestToken $token) {
		$this->requestToken = $token;

		return $this;
	}

	/**
	 *	$return OauthRequestToken
	 */
	protected function getRequestToken() {
		return $this->requestToken;
	}

	/**
	 *	Makes a HTTP request
	 */
	protected function makeRequest($url, $content = null, array $headers = array()) {
		$options = array(
			CURLOPT_URL => $url,
			CURLOPT_HEADER => false,
			CURLOPT_RETURNTRANSFER => 1,
			CURLOPT_USERAGENT => 'thegeektrawler (powered by cURL)',
			CURLOPT_AUTOREFERER => true,
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_FOLLOWLOCATION => 1,
			CURLOPT_FORBID_REUSE => 1,
			CURLOPT_TIMEOUT => 50,
			CURLOPT_HTTPHEADER => $headers
		);

		if (null !== $content) {
			$options = array_merge($options, array (
					CURLOPT_POST => 1,
					CURLOPT_POSTFIELDS => $content
				)
			);
		}

		$ch = curl_init();
		curl_setopt_array($ch, $options);

		$response = array(
			'content' => curl_exec($ch),
			'error' => curl_errno($ch),
			'httpcode' => 200
		);

		curl_close($ch);

		return $response;
	}

	/**
	 *	Normalize a URL as defined in the OAUTH spec
	 *
	 *	@param string $url
	 *	@param array $parameters
	 *	@return string
	 */
	protected function normalizeUrl($url, array $parameters = array()) {
		$normalizedUrl  = $url;
		$normalizedUrl .=
			(false !== strpos($url, '?') ? '&' : '?') .
			http_build_query($parameters, '', '&');

		return $normalizedUrl;
	}

	/**
	 *	@return string
	 */
	protected function getAuthorizationUrl() {
		$params = array(
			'response_type' => 'code',
			'client_id' => $this->ownerId,
			'scope' => $this->scope,
			'redirect_uri' => $this->redirectUri
		);

		return $this->normalizeUrl($this->urls['authorization_url'], $params);
	}

	/**
	 *	Returns true if fetch was successful, false otherwise
	 *
	 *	@return boolean
	 */
	protected function fetchAccessToken($code) {
		$params = array(
			'code' => $code,
			'grant_type' => 'authorization_code',
			'client_id' => $this->ownerId,
			'client_secret' => $this->ownerSecret,
			'redirect_uri' => $this->redirectUri
		);

		$response = $this->makeRequest($this->normalizeUrl($this->urls['access_token_url'], $params));

		if ($response['httpcode'] != 200 || $response['error'] != 0) {
			return false;
		}

		parse_str($response['content'], $values);

		$this->accessToken = new OauthAccessToken();
		$this->accessToken->setToken($values['access_token']);
		if (isset($values['refresh_token'])) {
			$this->accessToken->setRefreshToken($values['refresh_token']);
		}

		return true;
	}

	public function beginAuthorization() {
		header('Location: ' . $this->getAuthorizationUrl());
		exit();
	}

	/**
	 *	@return boolean
	 */
	public function continueAuthorization($code) {
		return $this->fetchAccessToken($code);
	}

	/**
	 *	@param string $raw_url
	 *	@param array $data
	 *	@param array $options
	 *	@return string | null
	 */
	public function makeApiRequest($raw_url, array $options = array(), array $data = null) {
		$params = array_merge(array(
				'access_token' => $this->accessToken->getToken()
			),
			$options
		);

		$url = $this->normalizeUrl($raw_url, $params);

		if (null != $data) {
			$response = $this->makeRequest($url, http_build_query($data));
		} else {
			$response = $this->makeRequest($url);
		}

		if ($response['error'] != 0) {
			return null;
		}

		return $response['content'];
	}
}

