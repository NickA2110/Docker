<?php
namespace Docker\Response;

use \Evenement\EventEmitter;

class Response extends EventEmitter implements ResponseStream {
	/**
	 *	Отдаем событие соответствующего типа
	 */
	static function Factory(array $aResponse): ResponseInterface {
		switch ($aResponse['Type']) {
			case 'container':
				return new Event\Container($aResponse);
				break;
			case 'network':
				return new Event\Network($aResponse);
				break;
			default:
				throw new Exception("Event type is known't", 1);
		}
	}
}