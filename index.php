<?php

require_once 'app/App.php';
require_once 'app/AppView.php';

try {
	$view = new AppView();
	$app = new App(AppHelper::$GOOGLE_APP, __DIR__ . '/config.params');
	$friends = $app->fetchFriends();

	$view->renderFriends($friends);
} catch (Exception $e) {
	$view->renderError($e->getMessage());
}

