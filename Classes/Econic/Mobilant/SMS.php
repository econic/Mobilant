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
	const API_URL = 'https://gw.mobilant.net';

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
	 * sends the sms and returns the status
	 * 
	 * @return array
	 */
	public function send() {
		if (
			!$this->getKey() ||
			!$this->getMessage() ||
			!$this->getTo() ||
			!$this->getFrom() ||
			!$this->getRoute() ) {
			return array(
				'success' => false
			);
		}

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, self::API_URL);
		curl_setopt($ch, CURLOPT_HEADER, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		// curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		// disable expect header "100 continue" to prefent HTTP 417 Statuscode
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Expect:'));
		curl_setopt($ch, CURLOPT_POSTFIELDS, array(
			// required params
			'key' => $this->getKey(),
			'message' => $this->getMessage(),
			'to' => $this->getTo(),
			'from' => $this->getFrom(),
			'route' => $this->getRoute(),

			// optional params
			'debug' => $this->getDebug() ? 1 : 0,
			'concat' => $this->getConcat() ? 1 : 0,
			'charset' => $this->getCharset(),
			'senddate' => $this->getSenddate(),
			'ref' => $this->getRef(),

			// output values
			'message_id' => '1',
			'cost' => '1',
			'count' => '1'
		));
		$response = explode("\n", curl_exec($ch));
		curl_close($ch);
		
		return array(
			'success' => $response[0] == 100 ? true : false,
			'response' => array(
				'responsecode' => $response[0],
				'message_id' => $response[1],
				'cost' => $response[2],
				'count' => $response[3]
			)
		);
	}

}
?>