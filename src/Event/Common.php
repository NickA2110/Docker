<?php
namespace Docker\Event;

class Common implements EventInterface {
	public $action;
	public $time;
	public $timeNano;
	
	function __construct(array $aEvent) {
		$this->action = (string) $aEvent['Action'];
		$this->time = (int) $aEvent['time'];
		$this->timeNano = (int) $aEvent['timeNano'];
	}
}