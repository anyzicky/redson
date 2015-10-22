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

	function __construct($address = "redis://localhost:6379")
	{
		$this->address = parse_url($address);
		$host = isset($this->address['host']) ? $this->address['host'] : 'localhost';
		$port = isset($this->address['port']) ? $this->address['port'] : '6379';

		$this->__redis = fsockopen($host, $port, $errno,$errstr);
		if(!$this->__redis) {
		  die($errno . "(" . $errstr . ")");
		}
	}

	function __destruct(){
		fclose($this->__redis);
	}
}