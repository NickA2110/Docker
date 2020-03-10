<?php
namespace Docker\Response\Event;

use Docker\Response\ResponseInterface;

class Common implements ResponseInterface {
	public $action;
	public $time;
	public $timeNano;
	
	function __construct(array $aResponse) {
		$this->action = (string) $aResponse['Action'];
		$this->time = (int) $aResponse['time'];
		$this->timeNano = (int) $aResponse['timeNano'];
	}
}