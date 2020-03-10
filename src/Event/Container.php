<?php
namespace Docker\Event;

class Container extends Common implements EventInterface {
	public $id; // ид контейнера
	public $image; // имя образа
	public $name; // имя контейнера

	function __construct(array $aEvent) {
		parent::__construct($aEvent);
		$this->id = $aEvent['id'];
		$this->image = $aEvent['from'];
		$this->name = $aEvent['Actor']['Attributes']['name'];
	}
}