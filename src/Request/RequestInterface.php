<?php
namespace Docker\Request;

interface RequestInterface {
	function __construct(string $command);
	public function getUri(): string;
}