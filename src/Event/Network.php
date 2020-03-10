<?php
namespace Docker\Event;

class Network extends Common implements EventInterface {
	public $id; // id сети
	public $name; // имя сети
	public $type; // тип сети
	public $containerId; // ид контейнера
	
	function __construct(array $aEvent) {
		parent::__construct($aEvent);
		$this->id = $aEvent['Actor']['ID'];
		$this->name = $aEvent['Actor']['Attributes']['name'];
		$this->type = $aEvent['Actor']['Attributes']['type'];
		$this->containerId = $aEvent['Actor']['Attributes']['container'];
	}
}