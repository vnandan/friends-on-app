<?php

require_once __DIR__ . '/OauthProvider.php';

class FacebookOauthProvider extends OauthProvider {

	public function __construct($scope = '') {
		parent::__construct();

		$this->urls = array(
			'authorization_url' => 'https://www.facebook.com/dialog/oauth',
			'access_token_url' => 'https://graph.facebook.com/oauth/access_token'
		);

		$this->scope = $scope;
	}
}
