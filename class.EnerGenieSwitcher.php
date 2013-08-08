<?php
class EnerGenieSwitcher {
	const ON = 1;
	const OFF = 0;

	private $debug = false;

	/**
	 * Check prerequisites and set-up
	 */
	public function __construct($ip, $debug=false) {
		if(!extension_loaded('curl')) { die('Fatal error: CURL extension needed.'); }
		$this->ip = $ip;
		$this->debug = $debug;
	}

	/**
	 * Do the switch
	 */
	public function doSwitch($switches) {
		foreach($switches as $port => $state) {
			$ports = array(1 => '', 2 => '', 3 => '', 4 => '');
			$ports[$port] = $state;
			$params = array();
			foreach($ports as $port => $state) {
				if(in_array($state, array(self::ON, self::OFF))) {
					$params['cte'.$port] = $state;
				}
			}
			$this->post_request('http://'.$this->ip, $params);
		}
	}

	function post_request($url, $fields) {
		$fields_string = '';
		foreach($fields as $key=>$value) { $fields_string .= $key.'='.$value.'&'; }
		rtrim($fields_string, '&');
		//open connection
		$ch = curl_init();

		// configure
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT_MS, 5000);
		//set the url, number of POST vars, POST data
		curl_setopt($ch,CURLOPT_URL, $url);
		curl_setopt($ch,CURLOPT_POST, count($fields));
		curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);

		//execute post
		$result = curl_exec($ch);
		if($this->debug === true) { echo "Calling " . $url . '?' . $fields_string . "\n"; }

		//close connection
		curl_close($ch);
	}
}
