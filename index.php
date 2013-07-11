<?php

require_once 'app/App.php';

try {
	$app = new App(AppHelper::$FACEBOOK_APP, __DIR__ . '/config.params');
	$friends = $app->fetchFriends();

	$app->renderFriendsView($friends);
} catch (Exception $e) {
	$app->renderErrorView('Something went wrong :(');
}

