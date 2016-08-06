<?php

namespace trashf;

require_once __DIR__ . '/AbstractScript.php';
class GetBalance extends AbstractScript {

	public function run() {
		$cli = fopen('php://stdin', 'r');
		while (empty($username)) {
			echo "Username?" . PHP_EOL;
			$username = trim(fgets($cli));
		}
		while (empty($password)) {
			echo "Password?" . PHP_EOL;
			$password = trim(fgets($cli));
		}
		fclose($cli);
		$userInfo = Veikkaus::getInstance()->login($username, $password);
//		var_dump($userInfo);
		var_dump(Veikkaus::getInstance()->getBalance());
	}

}
new GetBalance;
