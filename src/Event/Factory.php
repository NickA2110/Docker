<?php
namespace Docker\Event;

class Factory {
	/**
	 *	Отдаем событие соответствующего типа
	 */
	static function getEvent(array $aEvent): EventInterface {
		switch ($aEvent['Type']) {
			case 'container':
				return new Container($aEvent);
				break;
			case 'network':
				return new Network($aEvent);
				break;
			default:
				throw new Exception("Event type is known't", 1);
		}
	}
}