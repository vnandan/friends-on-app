<?php

/**
 *	Provides a read-only interface to get key-value
 *	pairs stored in a file
 *
 */
class Parameterized {

	private $filepath;
	private $handle;
	private $map;

	public function __construct($filepath) {
		if (file_exists($filepath)) {
			$this->filepath = $filepath;
			$this->handle = fopen($filepath, "r");

			$this->load();
		} else {
			throw new Exception("File does not exist!");
		}
	}

	public function __destruct() {
		fclose($this->handle);
	}

	/**
	 *	Load the config file into memory
	 */
	public function load() {
		while ($kv = fgets($this->handle)) {
			if (!$kv && ($kv == '\n' || strlen($kv) == 0)) {
				continue;
			}

			list ($key, $value) = explode(':', $kv, 2);
			$key = trim($key);
			$value = trim($value);

			if (strlen($key) > 0) {
				$this->map[$key] = $value;
			}
		}
	}

	/**
	 *	Check if a parameter is defined
	 *
	 *	@param string $key
	 *	@return boolean
	 */
	public function has($key) {
		return (true === array_key_exists($key, $this->map));
	}

	/**
	 *	Retrieve a parameter
	 *
	 *	@param string $key
	 *	@return string | null
	 */
	public function get($key) {
		if (isset($this->map[$key])) {
			return $this->map[$key];
		}

		return null;
	}
}

