<?php

namespace trashf;

require_once __DIR__ . '/AbstractScript.php';
class GetLotto extends AbstractScript {

	public function run() {
//		Veikkaus::getInstance()->setReturnMode('raw');
		for ($i = 1; $i < date('W'); $i++) {
			echo "Round $i of 2016";
			var_dump(json_decode(json_encode(Veikkaus::getInstance()->getLottoRound(2016, $i)), true));
			exit;
		}
	}

}
new GetLotto;
