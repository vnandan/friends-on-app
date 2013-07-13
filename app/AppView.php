<?php

class AppView {

	public function renderFriends($friends) {
		include 'templates/header.html.php';
		include 'templates/friends.html.php';
		include 'templates/footer.html.php';
	}

	public function renderError($theError) {
		include 'templates/header.html.php';
		include 'templates/error.html.php';
		include 'templates/footer.html.php';
	}
}

