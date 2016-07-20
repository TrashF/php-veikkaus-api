<?php

namespace trashf;
require_once __DIR__ . '/../vendor/autoload.php';

abstract class AbstractScript {

	abstract public function run();

	/**
	 * AbstractScript constructor.
	 */
	public function __construct() {
		try {
			$this->run();
		} catch (\Throwable $e) {
			var_dump($e);
		}
	}

}