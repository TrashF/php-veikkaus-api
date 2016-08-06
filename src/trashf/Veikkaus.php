<?php

namespace trashf;

/**
 * Class API
 * @package veikkaus
 */
class Veikkaus {

	const BASE_URL = 'https://www.veikkaus.fi/api/v1/';

	/**
	 * @var null|Veikkaus
	 */
	protected static $_instance = null;

	/**
	 * @var array
	 */
	protected $_cookies = array();

	/**
	 * @var string
	 */
	protected $_returnMode = 'objects';

	/**
	 * Disable cloning.
	 */
	public function __clone() {}

	/**
	 * Veikkaus constructor.
	 */
	public function __construct() {
		spl_autoload_register(function($class) {
			@include_once __DIR__ . '/../' . str_replace(array('\\', '_'), '/', $class) . '.php';
		});
	}

	/**
	 * @return Veikkaus
	 */
	public static function getInstance() {
		if (is_null(Veikkaus::$_instance)) {
			Veikkaus::$_instance = new Veikkaus();
		}
		return Veikkaus::$_instance;
	}

	/**
	 * @var array
	 */
	protected $_requiredHeaders = array(
		'X-ESA-API-Key' => 'ROBOT',
		'Content-Type' => 'application/json',
		'Accept' => 'application/json',
	);

	/**
	 * @param string $apiMethod
	 * @param array $params
	 * @param array $headers
	 * @param string $httpMethod
	 * @return array|string
	 * @throws \Exception
	 */
	protected function _request($apiMethod, array $params = array(), array $headers = array(), $httpMethod = 'GET') {
		$ctxOptions = array(
			'http' => array(
				'method' => $httpMethod,
				'header' => $this->_getHeaderString($headers),
				'ignore_errors' => true,
			)
		);
		$paramString = http_build_query($params, null, '&');
		if ($httpMethod === 'POST') {
			$ctxOptions['http']['content'] = json_encode($params);
			$ctxOptions['http']['header'] = $this->_getHeaderString($headers);
			$paramString = '';
		}
		$ctx = stream_context_create($ctxOptions);
		$apiUrl = $apiMethod;
		if (!empty($paramString)) {
			$apiUrl .= ('?' . $paramString);
		}
		$response = file_get_contents(Veikkaus::BASE_URL . $apiUrl, false, $ctx);
		if ($response === false) {
			throw new \Exception('API call failed.');
		}
		if ($apiMethod === 'sessions') {
			// save session for further use
			$this->_cookies = array();
			foreach ($http_response_header as $s) {
				if (preg_match('|^Set-Cookie:\s*([^=]+)=([^;]+);(.+)$|i', $s, $parts)) {
					$this->_cookies[$parts[1]] = $parts[2];
				}
			}
		}
		if (0 === mb_strpos($response, "\x1f" . "\x8b" . "\x08", 0, "US-ASCII")) {
			$response = gzdecode($response);
		}
		$parsedResponse = json_decode($response, true);
		return empty($parsedResponse) ? $response : $parsedResponse;
	}

	/**
	 * @param array $headers
	 * @return string
	 */
	protected function _getHeaderString(array $headers) {
		$headerString = '';
		$headers = array_merge($this->_requiredHeaders, $headers);
		if (!empty($this->_cookies)) {
			$headers['Cookie'] = "";
			foreach ($this->_cookies as $key => $value) {
				$headers['Cookie'] .= "{$key}={$value}; ";
			}
			$headers['Cookie'] = trim($headers['Cookie']);
		}
		foreach ($headers as $name => $value) {
			$headerString .= "{$name}: $value\r\n";
		}
		return $headerString;
	}

	/**
	 * @param string $returnMode
	 * @throws \Exception
	 */
	public function setReturnMode($returnMode) {
		if (is_string($returnMode) && in_array($returnMode, array('objects', 'raw'))) {
			$this->_returnMode = $returnMode;
		} else {
			throw new \Exception('Invalid return mode.');
		}
	}

	/**
	 * @return string
	 */
	public function getReturnMode() {
		return $this->_returnMode;
	}

	/**
	 * Get multiscore data (Moniveto)
	 *
	 * @param array $params
	 * @return array|string
	 */
	public function getMultiscore(array $params = array()) {
		$params['game-names'] = 'MULTISCORE';
		return $this->_request('sport-games/draws', $params);
	}

	/**
	 * Get score data (Tulosveto)
	 *
	 * @param array $params
	 * @return array|string
	 */
	public function getScore(array $params = array()) {
		$params['game-names'] = 'SCORE';
		return $this->_request('sport-games/draws', $params);
	}

	/**
	 * Get sport data (Vakio)
	 *
	 * @param array $params
	 * @return array|string
	 */
	public function getSport(array $params = array()) {
		$params['game-names'] = 'SPORT';
		return $this->_request('sport-games/draws', $params);
	}

	/**
	 * Get winner data (Voittajaveto)
	 *
	 * @param array $params
	 * @return array|string
	 */
	public function getWinner(array $params = array()) {
		$params['game-names'] = 'WINNER';
		return $this->_request('sport-games/draws', $params);
	}

	/**
	 * Get pick two data (Päivän pari)
	 *
	 * @param array $params
	 * @return array|string
	 */
	public function getPickTwo(array $params = array()) {
		$params['game-names'] = 'PICKTWO';
		return $this->_request('sport-games/draws', $params);
	}

	/**
	 * Get pick three data (Päivän trio)
	 *
	 * @param array $params
	 * @return array|string
	 */
	public function getPickThree(array $params = array()) {
		$params['game-names'] = 'PICKTHREE';
		return $this->_request('sport-games/draws', $params);
	}

	/**
	 * Get Perfecta data (Superkaksari)
	 *
	 * @param array $params
	 * @return array|string
	 */
	public function getPerfecta(array $params = array()) {
		$params['game-names'] = 'PERFECTA';
		return $this->_request('sport-games/draws', $params);
	}

	/**
	 * Get Trifecta data (Supertripla)
	 *
	 * @param array $params
	 * @return array|string
	 */
	public function getTrifecta(array $params = array()) {
		$params['game-names'] = 'TRIFECTA';
		return $this->_request('sport-games/draws', $params);
	}

	/**
	 * Get eBet data (Pitkäveto)
	 *
	 * @param array $params
	 * @return array|string
	 */
	public function getEBet(array $params = array()) {
		$params['game-names'] = 'EBET';
		return $this->_request('sport-games/draws', $params);
	}

	/**
	 * Get Ravi data (Moniveikkaus)
	 *
	 * @param array $params
	 * @return array|string
	 */
	public function getRavi(array $params = array()) {
		$params['game-names'] = 'RAVI';
		return $this->_request('sport-games/draws', $params);
	}

	/**
	 * @param array $params
	 * @return array|string
	 */
	public function getLotto(array $params = array()) {
		$params['game-names'] = 'LOTTO';
		if (empty($params['status'])) {
			$params['status'] = 'RESULTS_AVAILABLE';
		}
		$response = $this->_request('draw-games/draws', $params);
		if ($this->getReturnMode() === 'raw' || empty($response['draws'])) {
			return $response;
		}
		$mangledResponse = array();
		foreach ($response['draws'] as $draw) {
			$mangledResponse[] = new Results_Lotto($draw);
		}
		return $mangledResponse;
	}

	/**
	 * @param \DateTime $from
	 * @param \DateTime|null $to
	 * @return array|string
	 */
	public function getLottoRounds(\DateTime $from, \DateTime $to = null) {
		if (is_null($to)) {
			$to = new \DateTime();
			$to->setTime(23, 0);
		}
		return $this->getLotto(array('date-from' => $from->format('Uu')/1000, 'date-to' => $to->format('Uu')/1000));
	}

	/**
	 * @param null|int $year
	 * @param null|int $round
	 * @return mixed|null
	 */
	public function getLottoRound($year = null, $round = null) {
		if (is_null($year)) {
			$year = date('Y', strtotime('friday last week'));
		}
		if (is_null($round)) {
			$round = date('W', strtotime('friday last week'));
		}
		$start = new \DateTime();
		$start->setTimezone(new \DateTimeZone('Europe/Helsinki'));
		$start->setISODate($year, $round);
		$start->setTime(0, 0);
		$end = clone $start;
		$end->modify('+5 days');
		$end->setTime(23, 0);
		$rounds = $this->getLottoRounds($start, $end);
		return empty($rounds) ? null : reset($rounds);
	}

	/**
	 * Performs login and returns basic user information.
	 *
	 * @param string $login
	 * @param string $password
	 * @return array|string
	 */
	public function login($login, $password) {
		return $this->_request(
			'sessions',
			array(
				'type' => 'STANDARD_LOGIN',
				'login' => $login,
				'password' => $password
			),
			array(),
			'POST'
		);
	}

	/**
	 * @return array|string
	 * @throws \Exception
	 */
	public function getBalance() {
		if (empty($this->_cookies)) {
			throw new \Exception('Trying to use getBalance() before calling login()');
		}
		return $this->_request('players/self/account');
	}

	/**
	 * @return array
	 */
	public function getCookies() {
		return $this->_cookies;
	}

	/**
	 * @param array $cookies
	 */
	public function setCookies(array $cookies = array()) {
		$this->_cookies = $cookies;
	}

}