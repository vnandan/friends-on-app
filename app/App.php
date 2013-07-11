<?php

require_once __DIR__ . '/UserData.php';
require_once __DIR__ . '/AppBase.php';
require_once __DIR__ . '/AppHelper.php';
require_once __DIR__ . '/../oauth/OauthProviderFactory.php';
require_once __DIR__ . '/../util/Parameterized.php';

class App extends AppBase {

	private $appType;

	/**
	 *	OAUTH provider
	 */
	private $provider;

	public function __construct($type, $configpath) {
		$this->appType = $type;

		$params = new Parameterized($configpath);

		$this->provider = OauthProviderFactory::create($type);

		$this->provider
			->setRedirectUri(AppHelper::fullUrl())
			->setOwnerId($params->get(strtoupper($type) . '_APP_ID'))
			->setOwnerSecret($params->get(strtoupper($type) . '_APP_SECRET'));

		if (!isset($_GET['code'])) {
			$this->provider->beginAuthorization();
		} else {
			if (false === $this->provider->continueAuthorization($_GET['code'])) {
				$this->renderErrorView('Authorization failed!');
			}
		}
	}

	/**
	 *	Fetch friends as UserData instances
	 */
	public function fetchFriends() {
		switch ($this->appType) {
			case 'facebook':
				return $this->fetchFacebookFriends();
		}

		return null;
	}

	public function renderFriendsView($friends) {
		if ($friends == null) {
			$friends = false;
		}

		extract($friends);

		include 'templates/header.html.php';
		include 'templates/friends.html.php';
		include 'templates/footer.html.php';
	}

	public function renderErrorView($theError) {
		extract($theError);

		include 'templates/header.html.php';
		include 'templates/error.html.php';
		include 'templates/footer.html.php';
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
}
