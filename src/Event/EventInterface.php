<?php
namespace Docker\Event;

interface EventInterface {
	function __construct(array $aEvent);
}