<?php
namespace Docker;

use \React\HttpClient\Client;
use \Docker\Request\RequestInterface;
use \Docker\Response\ResponseStream;
use \Docker\Response\Response;

class Docker {
	static $docker = null;

	private $connector;
	private $loop;

	function __construct($connector, $loop) {
		$this->connector = $connector;
		$this->loop = $loop;
	}

	function request(RequestInterface $oRequest): ResponseStream {
		$connector = $this->connector;
		$client = new Client($this->loop, $connector);

		$oResponse = new Response();
		
		$uri = $oRequest->getUri();
		if ($connector instanceof \React\Socket\FixedUriConnector) {
			// костыль для unix socket
			$uri = 'http://docker' . $uri;
		}
		$request = $client->request('GET', $uri);

		$request->on('response', function ($response) use ($connector, $oResponse) {
		    $response->on('data', function ($sChunk) use ($oResponse) {
		        $aEvent = json_decode($sChunk, true);
		        if (is_array($aEvent)) {
		        	if (!empty($aEvent['Type'])) {
		        		$oResponse->emit($aEvent['Type'], [Response::Factory($aEvent)]);
		        	}
		        	$oResponse->emit('*', [Response::Factory($aEvent)]);
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

	static function Factory($connector, $loop) {
		static::$docker = new static($connector, $loop);
		return static::$docker;
	}

	static function Instance() {
		return static::$docker;
	}
}