<?php
namespace Docker\Container;

use Docker\Docker;
use Docker\Request\Request;
use Docker\Response\Event\Container as EventContainer;
use Docker\Response\ResponseInterface;	

class Container implements ContainerInterface {
	public $id = null;

	function __construct(string $id) {
		$this->id = $id;
	}

	public function getInfo() {
		$docker = Docker::Instance();
		$request = new Request("/v1.24/containers/{$this->id}/json");
		$events = $docker->request($request);
		$events->on('*', function(ResponseInterface $oContainer) {
			print_r($oContainer->attributes);
		});
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