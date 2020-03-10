<?php
namespace Docker\Request;

class Request implements RequestInterface {
	public $method = '';
	public $filters = [];

	function __construct(string $method) {
		$this->method = $method;
	}

	/**
	 *	[
	 *		'event' => ['connect', 'disconnect', 'start', 'stop', 'die', 'destroy'],
	 *		'type' => ['container', 'network']
	 *	]
	 */
	public function filters(array $filters) {
		$this->filters = $filters;
	}

	public function getUri(): string {
		$uri = $this->method;
		
		$params = [];

		if (!empty($this->filters)) {
			$params['filters'] = json_encode($this->filters);
		}

		if (!empty($params)) {
			$uri .= '?' . http_build_query($params);
		}

		return $uri;
	}
}