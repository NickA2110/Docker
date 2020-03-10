<?php
namespace Docker\Response;

use \Evenement\EventEmitter;
use \Docker\Container\Response as ContainerResponse;

class Response extends EventEmitter implements ResponseStream {
	/**
	 *	Отдаем событие соответствующего типа
	 */
	static function Factory(array $aResponse): ResponseInterface {
		if (!empty($aResponse['Type'])) {
			switch ($aResponse['Type']) {
				case 'container':
					return new Event\Container($aResponse);
				case 'network':
					return new Event\Network($aResponse);
				default:
					throw new \Exception("Event type is known't", 1);
			}
		} elseif (!empty($aResponse['HostsPath'])) {
			return new ContainerResponse($aResponse);
		}
		throw new \Exception("Event type is known't", 1);
	}
}