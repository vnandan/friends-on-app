<?php

class AppBase {

	/**
	 *	Does a HTTP-POST request
	 */
	public function post($url, $content) {
		header('POST ' . $url . ' HTTP/1.1');
		header('Host: ' . $_SERVER['HTTP_HOST']);
		header('Connection: close');
		header('Content-Type: application/x-www-form-urlencoded');
		header('Content-Length: ' . strlen($content));
		header('');
		header($content);

		exit();
	}

	/**
	 *	Does a HTTP-GET request
	 */
	public function get($url, $content = null) {
		if (null === $content) {
			header('Location: ' . $url);
			exit();
		}

		header('GET ' . $url . ' HTTP/1.1');
		header('Host: ' . $_SERVER['HTTP_HOST']);
		header('Connection: close');
		header('Content-Type: text/html');
		header('Content-Length: ' . strlen($content));
		header('');
		header($content);

		exit();
	}
}

