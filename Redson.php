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
	private $__scheme = "redis://";
	public $host;
	public $port;
	
	function __construct($host = $this->$__scheme."localhost", $port = "6379")
	{
		$this->$__redis = fsockopen(, $arUrl['port'], $errno,$errstr);
		if(!$this->$__redis) {
		  die($errno . "(" . $errstr . ")");
		}
	}

	function __destruct(){
		fclose($this->$__redis);
	}
}