<?php

class AppHelper {

	public static $FACEBOOK_APP = 'facebook';
	public static $GOOGLE_APP = 'google';
	public static $TWITTER_APP = 'twitter';

	public static function fullUrl() {
		$s = empty($_SERVER["HTTPS"]) ? '' : ($_SERVER["HTTPS"] == "on") ? "s" : "";
		$sp = strtolower($_SERVER["SERVER_PROTOCOL"]);
		$protocol = substr($sp, 0, strpos($sp, "/")) . $s;
		$port = ($_SERVER["SERVER_PORT"] == "80") ? "" : (":".$_SERVER["SERVER_PORT"]);
		return $protocol . "://" . $_SERVER['SERVER_NAME'] . $port . $_SERVER['PHP_SELF'];
	}
}

