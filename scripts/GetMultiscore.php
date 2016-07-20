<?php

namespace trashf;

require_once __DIR__ . '/AbstractScript.php';
class GetMultiscore extends AbstractScript {

	public function run() {
		var_dump(Veikkaus::getInstance()->getMultiscore());
	}

}
new GetMultiscore();
