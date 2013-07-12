<?php

require_once __DIR__ . '/UserData.php';
require_once __DIR__ . '/AppBase.php';
require_once __DIR__ . '/AppHelper.php';
require_once __DIR__ . '/../oauth/OauthAccessToken.php';
require_once __DIR__ . '/../oauth/OauthProviderFactory.php';
require_once __DIR__ . '/../util/Parameterized.php';

class App extends AppBase {

	private $appType;
	private $configPath;

	/**
	 *	OAUTH provider
	 */
	private $provider;

	public function __construct($type, $configpath) {
		$this->appType = $type;
		$this->configPath = $configpath;

		$params = new Parameterized($configpath);

		$this->provider = OauthProviderFactory::create($type);

		$this->provider
			->setRedirectUri(AppHelper::fullUrl())
			->setOwnerId($params->get(strtoupper($type) . '_APP_ID'))
			->setOwnerSecret($params->get(strtoupper($type) . '_APP_SECRET'));

		if ($params->has(strtoupper($type) . '_APP_SCOPE')) {
			$this->provider->setScope($params->get(strtoupper($type) . '_APP_SCOPE'));
		}

		// Authorize User
		if (!isset($_COOKIE[$type . '_access_token'])) {
			if (!isset($_GET['code'])) {
				$this->provider->beginAuthorization();
			} else {
				if (false === $this->provider->continueAuthorization($_GET['code'])) {
					throw new Exception("Authorization failed!");
				} else {
					setcookie(
						$type . '_access_token',
						$this->provider->getAccessToken()->getToken(),
						time() + 3600, /* One hour */
						'/friends-on-app/',
						$_SERVER['HTTP_HOST'],
						false,
						true
					);
				}
			}
		} else {
			$token = new OauthAccessToken();
			$this->provider->setAccessToken($token->setToken($_COOKIE[$type . '_access_token']));
		}
	}

	/**
	 *	Fetch friends as UserData instances
	 */
	public function fetchFriends() {
		switch ($this->appType) {
			case 'facebook':
				return $this->fetchFacebookFriends();
			case 'google':
				return $this->fetchGoogleFriends();
		}

		return null;
	}

	private function fetchFacebookFriends() {
		$url = 'https://graph.facebook.com/fql';
		$options = array (
			'q' => 'SELECT pic_small, name FROM user WHERE uid IN (SELECT uid2 FROM friend WHERE uid1 = me()) AND is_app_user = 1'
		);

		$response = json_decode($this->provider->makeApiRequest($url, $options));
		$ret = null;

		if ($response) {
			foreach ($response->data as $obj) {
				$user = new UserData();
				$ret[] = $user->setName($obj->name)->setImageUrl($obj->pic_small);
			}
		}

		return $ret;
	}

	private function fetchGoogleFriends() {
		$url = 'https://www.googleapis.com/plus/v1/people/me/people/visible';

		$params = new Parameterized($this->configPath);
		$options = array (
			'key' => $params->get('GOOGLE_APP_KEY')
		);

		$response = json_decode($this->provider->makeApiRequest($url, $options));
		$ret = null;

		if ($response) {
			foreach ($response->items as $obj) {
				$user = new UserData();
				$ret[] = $user->setName($obj->displayName)->setImageUrl($obj->image->url);
			}
		}

		return $ret;
	}
}

