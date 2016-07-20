<?php

namespace trashf;

/**
 * Class Results_Lotto
 * @package trashf
 */
class Results_Lotto extends Results_AbstractResult {

	/**
	 * @return array
	 */
	public function getNumbers() {
		return array(
			'primary' => $this->getPrimaryNumbers(),
			'secondary' => $this->getSecondaryNumbers(),
			'multiplier' => $this->getMultiplierNumber(),
		);
	}

	/**
	 * @return array|null
	 */
	public function getPrimaryNumbers() {
		return array_map('intval', $this->getRawData('results->0->primary', array()));
	}

	/**
	 * @return array|null
	 */
	public function getSecondaryNumbers() {
		return array_map('intval', $this->getRawData('results->0->secondary', array()));
	}

	/**
	 * @return int|null
	 */
	public function getMultiplierNumber() {
		return $this->getRawData('results->0->multiplier');
	}

	/**
	 * @return \DateTime|false
	 */
	protected function _getDrawtime() {
		$drawTime = $this->getRawData('drawTime');
		if (empty($drawTime)) {
			return null;
		}
		return \DateTime::createFromFormat('U', $drawTime/1000);
	}

	/**
	 * @return int|null
	 */
	public function getYear() {
		$drawTime = $this->_getDrawtime();
		return is_null($drawTime) ? null : intval($drawTime->format('Y'));
	}

	/**
	 * @return int|null
	 */
	public function getRound() {
		$drawTime = $this->_getDrawtime();
		return is_null($drawTime) ? null : intval($drawTime->format('W'));
	}

	/**
	 * @return array
	 */
	public function jsonSerialize() {
		if (Veikkaus::getInstance()->getReturnMode() === 'raw') {
			return $this->getRawData();
		}
		return array(
			'year' => $this->getYear(),
			'round' => $this->getRound(),
			'numbers' => $this->getNumbers(),
		);
	}

}