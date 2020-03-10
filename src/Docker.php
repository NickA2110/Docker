<?php
namespace Docker;

use \React\HttpClient\Client;
use \React\Socket\FixedUriConnector;
use \React\Socket\UnixConnector;
use \Docker\Event\Factory as EventFactory;
use \Docker\Event\WorkerInterface as EventWorkerInterface;
use \Docker\Watch\Stream as WatchStream;
use \Docker\Watch\StreamInterface as WatchStreamInterface;

class Docker {
	private $loop;

	function __construct($loop) {
		$this->loop = $loop;
	}

	/*
	function request($connector, string $command) {
		$connector = new FixedUriConnector(
		    'unix:///var/run/docker.sock',
		    new UnixConnector($this->loop)
		);
		$client = new Client($this->loop, $connector);
		$request = $client->request('GET', 'http://docker/v1.24/events');
		$request->on('response', function ($response) use ($connector) {
		    $response->on('data', function ($chunk) {
		        $res = json_decode($chunk, true);
		        print_r($res);
		    });
		    $response->on('end', function() use ($connector) {
		        unset($connector);
		    });
		});
		$request->on('error', function (\Exception $e) {
		    echo $e;
		});
		$request->end();
	}
	*/

	/**
	 *	[
	 *		'event' => ['connect', 'disconnect', 'start', 'stop', 'die', 'destroy'],
	 *		'type' => ['container', 'network']
	 *	]
	 */
	function watch($connector, array $aFilter): WatchStreamInterface {
		$client = new Client($this->loop, $connector);

		$oStream = new WatchStream();
		
		$uri = 'http://docker/v1.24/events?';
		if (!empty($aFilter)) {
			$uri .= 'filters=' . json_encode($aFilter);
		};
		$request = $client->request('GET', $uri);

		$request->on('response', function ($response) use ($connector, $oStream) {
		    $response->on('data', function ($sChunk) use ($oStream) {
		        $aEvent = json_decode($sChunk, true);
		        if (is_array($aEvent)) {
		        	$oStream->emit($aEvent['Type'], [EventFactory::getEvent($aEvent)]);
		        	$oStream->emit('all', [EventFactory::getEvent($aEvent)]);
		        }
		    });
		    $response->on('end', function() use ($connector, $oStream) {
		    	$oStream->removeAllListeners();
		    	unset($oStream);
		        unset($connector);
		    });
		});

		$request->on('error', function (\Exception $e) use ($connector, $oStream) {
			$oStream->emit('error', $e);
			unset($oStream);
		    unset($connector);
		});

		$request->end();

		return $oStream;
	}
}