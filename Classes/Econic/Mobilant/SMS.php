<?php
namespace Econic\Mobilant;

/**
 * PHP class for the mobilant.de services
 * API: http://mobilant.de/
 *
 * @author Aimo Künkel
 * @package mobilant
 * @license MIT License
 */
class SMS {

	// api url
	const API_URL = 'https://gw.mobilant.net/';

	/**
	 * Identification Key
	 *
	 * @var string
	 */
	protected $key;

	/**
	 * text of the message
	 *
	 * @var string
	 */
	protected $message;

	/**
	 * phone number of the receiver
	 *
	 * @var string
	 */
	protected $to;

	/**
	 * sender name
	 *
	 * @var string
	 */
	protected $from;

	/**
	 * the route to send the sms with, check available options on mobilant.com
	 *
	 * @var string
	 */
	protected $route;

	/**
	 * The encoding of the string you pass into setMessage() and setFrom()
	 *
	 * @var string
	 */
	protected $charset;

	/**
	 * reference of the sms, if you wish to add one
	 *
	 * @var string
	 */
	protected $ref;

	/**
	 * concat, tells if messages > 160 chars should be splitted or concatenated
	 *
	 * @var boolean
	 */
	protected $concat;

	/**
	 * senddate if you wish to time the sms
	 *
	 * @var integer
	 */
	protected $senddate;

	/**
	 * send debugging sms
	 *
	 * @var boolean
	 */
	protected $debug;

	/**
	 * Gets the key
	 *
	 * @return string
	 */
	public function getKey() {
		return $this->key;
	}

	/**
	 * Sets the key
	 *
	 * @param string $key value to set
	 * @return this
	 */
	public function setKey($key) {
		$this->key = (string)$key;
		return $this;
	}

	/**
	 * Gets the message
	 *
	 * @return string
	 */
	public function getMessage() {
		return $this->message;
	}

	/**
	 * Sets the message
	 *
	 * @param string $message value to set
	 * @return this
	 */
	public function setMessage($message) {
		$this->message = (string)$message;
		return $this;
	}

	/**
	 * Gets the to
	 *
	 * @return string
	 */
	public function getTo() {
		return $this->to;
	}

	/**
	 * Sets the to
	 *
	 * @param string $to value to set
	 * @return this
	 */
	public function setTo($to) {
		$this->to = (string)$to;
		return $this;
	}

	/**
	 * Gets the from
	 *
	 * @return string
	 */
	public function getFrom() {
		return $this->from;
	}

	/**
	 * Sets the from
	 *
	 * @param string $from value to set
	 * @return this
	 */
	public function setFrom($from) {
		$this->from = (string)$from;
		return $this;
	}

	/**
	 * Gets the route
	 *
	 * @return string
	 */
	public function getRoute() {
		return $this->route;
	}

	/**
	 * Sets the route
	 *
	 * @param string $route value to set
	 * @return this
	 */
	public function setRoute($route) {
		$this->route = (string)$route;
		return $this;
	}

	/**
	 * Gets the charset
	 *
	 * @return string
	 */
	public function getCharset() {
		return $this->charset;
	}

	/**
	 * Sets the charset
	 *
	 * @param string $charset value to set
	 * @return this
	 */
	public function setCharset($charset) {
		$this->charset = (string)$charset;
		return $this;
	}

	/**
	 * Gets the ref
	 *
	 * @return string
	 */
	public function getRef() {
		return $this->ref;
	}

	/**
	 * Sets the ref
	 *
	 * @param string $ref value to set
	 * @return this
	 */
	public function setRef($ref) {
		$this->ref = (string)$ref;
		return $this;
	}

	/**
	 * Gets the concat
	 *
	 * @return boolean
	 */
	public function getConcat() {
		return $this->concat;
	}

	/**
	 * Sets the concat
	 *
	 * @param boolean $concat value to set
	 * @return this
	 */
	public function setConcat($concat) {
		$this->concat = (boolean)$concat;
		return $this;
	}

	/**
	 * Gets the senddate
	 *
	 * @return integer
	 */
	public function getSenddate() {
		return $this->senddate;
	}

	/**
	 * Sets the senddate
	 *
	 * @param integer $senddate value to set
	 * @return this
	 */
	public function setSenddate($senddate) {
		$this->senddate = (integer)$senddate;
		return $this;
	}

	/**
	 * Gets the debug
	 *
	 * @return boolean
	 */
	public function getDebug() {
		return $this->debug;
	}

	/**
	 * Sets the debug
	 *
	 * @param boolean $debug value to set
	 * @return this
	 */
	public function setDebug($debug) {
		$this->debug = (boolean)$debug;
		return $this;
	}

	/**
	 * Sends the SMS and returns the response
	 *
	 * @return array|null
	 */
	public function send() {
		// Try running the request, up to five times
		for ($i = 0; $i < 5; $i++) {
			$response = $this->request();

			if ($response['success']) {
				$response['retry_count'] = $i;

				return $response;
			}
		}

		return null;
	}

	/**
	 * Send the request to the Mobilant gateway
	 *
	 * @return array[status:int, success:boolean, ...]
	 */
	protected function request() {
		// Build the POST data array
		$data = $this->buildPostData();

		if (!is_array($data)) {
			return [
				'status' => -1,
				'success' => false,
				'error' => 'Could not build valid POST data array!'
			];
		}

		$data['message_id'] = '1';
		$data['cost'] = '1';
		$data['count'] = '1';

		// Create cURL instance and set options
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, self::API_URL);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 3);
		curl_setopt($ch, CURLOPT_TIMEOUT, 5);
		curl_setopt($ch, CURLOPT_HEADER, false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		curl_setopt($ch, CURLOPT_HTTPHEADER, ['Expect:']); // disable expect header "100 continue" to prevent HTTP 417 Statuscode

		// Send the request and terminate cURL instance
		$response = curl_exec($ch);
		$err = curl_error($ch);
		curl_close($ch);

		// Did everything go well?
		if (is_string($response) && empty($err)) {
			$response = explode("\n", $response);
			$status = (int)($response[0] ?? 0);

			return [
				'status' => $status,
				'success' => ($status === 100 ? true : false),
				'message_id' => ($response[1] ?? ''),
				'cost' => ($response[2] ?? ''),
				'count' => ($response[3] ?? '')
			];
		} else {
			return [
				'status' => 0,
				'success' => false,
				'error' => $err
			];
		}
	}

	/**
	 * Builds an array that contains all values for the POST body
	 *
	 * @return array|null
	 */
	protected function buildPostData() {
		if (
			!$this->getKey()     ||
			!$this->getMessage() ||
			!$this->getTo()      ||
			!$this->getFrom()    ||
			!$this->getRoute()
		) {
			return null;
		}

		return [
			// required params
			'key' => $this->getKey(),
			'message' => $this->getMessage(),
			'to' => $this->getTo(),
			'from' => $this->getFrom(),
			'route' => $this->getRoute(),

			// optional params
			'debug' => $this->getDebug() ? 1 : 0,
			'concat' => $this->getConcat() ? 1 : 0,
			'charset' => $this->getCharset() ?? mb_internal_encoding(),
			'senddate' => $this->getSenddate(),
			'ref' => $this->getRef()
		];
	}

}
