<?php

namespace trashf;

/**
 * Class Results_AbstractResult
 * @package trashf
 */
abstract class Results_AbstractResult implements \JsonSerializable {

	const KEY_NOTATION_SEPARATOR = '->';

	/**
	 * @var array
	 */
	protected $_rawData = array();

	/**
	 * Results_AbstractResult constructor.
	 * @param array $rawData
	 */
	public function __construct(array $rawData) {
		$this->setRawData($rawData);
	}

	/**
	 * @param array $rawData
	 */
	public function setRawData(array $rawData) {
		$this->_rawData = $rawData;
	}

	/**
	 * @param null|string $key
	 * @param null|mixed $default
	 * @return array|mixed
	 */
	public function getRawData($key = null, $default = null) {
		if (is_null($key)) {
			return $this->_rawData;
		}
		return $this->_seekArray($this->_rawData, $key, $default);
	}

	/**
	 * @param array $data
	 * @param string $key
	 * @param null|mixed $default
	 * @return mixed
	 */
	protected function _seekArray(array $data, $key, $default) {
		if (strlen($key) == 0) {
			return $default;
		}
		if (strpos($key, Results_AbstractResult::KEY_NOTATION_SEPARATOR) === false) {
			return isset($data[$key]) ? $data[$key] : $default;
		}
		$keyArray = explode(Results_AbstractResult::KEY_NOTATION_SEPARATOR, $key);
		$currentKey = array_shift($keyArray);
		if (!isset($data[$currentKey])) {
			return $default;
		}
		return $this->_seekArray($data[$currentKey], implode(Results_AbstractResult::KEY_NOTATION_SEPARATOR, $keyArray), $default);
	}

	/**
	 * @return array
	 */
	public function jsonSerialize() {
		return $this->getRawData();
	}

}