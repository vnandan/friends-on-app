<?php

require_once __DIR__ . '/FacebookOauthProvider.php';
require_once __DIR__ . '/GoogleOauthProvider.php';

/**
 *	Factory class to create valid provider objects
 */
class OauthProviderFactory {

	public static function create($type, $scope = '') {
		switch ($type) {
		case 'facebook':
			return new FacebookOauthProvider($scope);
		case 'google':
			return new GoogleOauthProvider($scope);
		}

		return null;
	}
}
