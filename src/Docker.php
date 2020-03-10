<?php
namespace Docker;

use \React\HttpClient\Client;
use \Docker\Request\RequestInterface;
use \Docker\Response\ResponseStream;
use \Docker\Response\Response;

class Docker {
	private $loop;

	function __construct($loop) {
		$this->loop = $loop;
	}

	/**
	 *	[
	 *		'event' => ['connect', 'disconnect', 'start', 'stop', 'die', 'destroy'],
	 *		'type' => ['container', 'network']
	 *	]
	 */
	function request($connector, RequestInterface $oRequest): ResponseStream {
		$client = new Client($this->loop, $connector);

		$oResponse = new Response();
		
		$uri = $oRequest->getUri();
		if ($connector instanceof \React\Socket\FixedUriConnector) {
			$uri = 'http://docker' . $uri;
		}
		$request = $client->request('GET', $uri);

		$request->on('response', function ($response) use ($connector, $oResponse) {
		    $response->on('data', function ($sChunk) use ($oResponse) {
		        $aEvent = json_decode($sChunk, true);
		        if (is_array($aEvent)) {
		        	$oResponse->emit($aEvent['Type'], [Response::Factory($aEvent)]);
		        	$oResponse->emit('all', [Response::Factory($aEvent)]);
		        }
		    });
		    $response->on('end', function() use ($connector, $oResponse) {
		    	$oResponse->removeAllListeners();
		    	unset($oResponse);
		        unset($connector);
		    });
		});

		$request->on('error', function (\Exception $e) use ($connector, $oResponse) {
			$oResponse->emit('error', $e);
			unset($oResponse);
		    unset($connector);
		});

		$request->end();

		return $oResponse;
	}
}