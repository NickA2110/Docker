<?php
namespace Docker\Container;

use Docker\Response\ResponseInterface;

class Response extends Container implements ResponseInterface {
	public $action;
	public $name;
	public $attributes = [];
	
	function __construct(array $aResponse) {
		$this->id = (string) $aResponse['Id'];
		$this->name = (int) $aResponse['Name'];
		$this->attributes = $aResponse;
	}
}