<?php
namespace Docker\Container;

use Docker\Event\Container as EventContainer;

class Container {
	public $id = null;

	function __construct(string $id) {
		$this->id = $id;
	}

	public function getInfo() {
		
	}

	static function Factory($element) :ContainerInterface {
		if (is_string($element)) {
			return new Container($element);
		} elseif ($element instanceof EventContainer) {
			return new Container($element->id);
		} else {
			throw new \Exception("No item type if factory");
		}
	}
}