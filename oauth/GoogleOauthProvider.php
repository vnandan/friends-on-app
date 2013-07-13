<?php

require_once __DIR__ . '/OauthProvider.php';

class GoogleOauthProvider extends OauthProvider {

	public function __construct($scope = '') {
		parent::__construct();

		$this->urls = array(
			'authorization_url' => 'https://accounts.google.com/o/oauth2/auth',
			'access_token_url' => 'https://accounts.google.com/o/oauth2/token'
		);

		$this->setScope($scope);
	}

	/**
	 *	@Override
	 */
	public function makeApiRequest($raw_url, array $options = array(), array $data = null) {
		$url = $this->normalizeUrl($raw_url, $options);

		$headers = array(
			'Authorization: Bearer ' . $this->accessToken->getToken()
		);

		if (null != $data) {
			$response = $this->makeRequest($url, http_build_query($data), $headers);
		} else {
			$response = $this->makeRequest($url, null, $headers);
		}

		if ($response['error'] != 0) {
			return null;
		}

		return $response['content'];
	}
}

