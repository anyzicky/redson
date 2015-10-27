<?php

/**
 * Redson it's USSR Superman :)
 * Redson just simple interface for Redis DB
 * @author Suraev Denis <any.zicky@gmail.com>
 * @copyright 2015 Suraev Denis <any.zicky@gmail.com>
 * @package Redson
 */

namespace Redson;

/**
* 
*/
class Redis
{

	private $__redis;
	public $address;

	function __construct($address = "redis://localhost:6379") {
		$this->address = parse_url($address);
		$host = isset($this->address['host']) ? $this->address['host'] : 'localhost';
		$port = isset($this->address['port']) ? $this->address['port'] : '6379';

		$this->__redis = fsockopen($host, $port, $errno,$errstr);
		if(!$this->__redis) {
		  throw new Exception("Error $errno ($errstr)", 1);
		}
	}

	function __destruct() {
		fclose($this->__redis);
	}


	function __call($name, $arguments) {
		array_unshift($arguments, $name);
		$cmd = "*" . count($arguments) . "\r\n";
		foreach($arguments as $arg) {
			$cmd .= "$" . strlen($arg) . "\r\n" . $arg . "\r\n";
		}
		$this->send($cmd);
		$ret = $this->response();
		return $ret;
	}

	/**
	 * power superman power ping redis server :) 
	 * @return string just answer +PONG
	 */
	function power() {
		$this->send("*1\r\n\$4\r\nPING\r\n");
		$respond = fgets($this->__redis, 512);
		return $respond;
	}

	/**
	 * send write command to redis socket 
	 * @param  string $cmd specific redis command
	 * @return string      error?
	 */
	private function send($cmd) {
		$write = fwrite($this->__redis, $cmd);
		if(!$write){
			throw new Exception("Error: no send cmd...", 1);
			
		}
	}

	/**
	 * response read from redis socket and parse respond answer's
	 * @return mixin error?/string/bool answer read from redis socket
	 */
	private function response() {
		/*
		In RESP, the type of some data depends on the first byte:
		    
	    For Simple Strings the first byte of the reply is "+"
	    For Errors the first byte of the reply is "-"
	    For Integers the first byte of the reply is ":"
	    For Bulk Strings the first byte of the reply is "$"
	    For Arrays the first byte of the reply is "*"

		 */
		$response = false;
		$respond = fgets($this->__redis, 512);
		//echo substr($response, 1);
		switch ($respond[0]) {
			case '+':
				if(substr($respond, 1) == 'OK')
					$response = true;
				break;
			case '-':
				throw new Exception($respond, 1);
				break;
			case '$':
				$countResp = intval(substr($respond,1));
				if($countResp == -1) {
					//Null bulk 
					$response = NULL;
				} else {
					$resp = fread($this->__redis, $countResp+2);//+2 for \r\n
					if(!$resp)
						throw new Exception("Response error ".$response, 1);
					else
						$response = $resp;
				}
				break;
			default:
				throw new Exception("Unknow response...", 1);
				break;
		}

		return $response;
	}
}