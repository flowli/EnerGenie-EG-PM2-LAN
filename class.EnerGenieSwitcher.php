<?php
class EnerGenieSwitcher {
	const ON = 1;
	const OFF = 0;

	private $debug = false;

	/**
	 * Check prerequisites and set-up
	 */
	public function __construct($ip, $password, $debug=false) {
		if(!extension_loaded('curl')) { die('Fatal error: CURL extension needed.'); }
		$this->ip = $ip;
		$this->password = $password;
		$this->debug = $debug;
	}

	public function doLogin() {
		$this->postRequest('http://'.$this->ip.'/login.html', array('pw' => $this->password));
	}

	/**
	 * Get status
	 */
	public function getStatus() {
		$this->doLogin();
		$html = $this->getRequest('http://'.$this->ip.'/energenie.html', array());
		preg_match_all('/var sockstates \= \[([0-1],[0,1],[0,1],[0,1])\]/', $html, $matches);
		if(!isset($matches[1][0])) { return false; }
		$states = explode(',', $matches[1][0]);
		return array(1=>$states[0], 2=>$states[1], 3=>$states[2], 4=>$states[3]);
	}

	/**
	 * Do the switch
	 */
	public function doSwitch($switches) {
		$this->doLogin();
		foreach($switches as $port => $state) {
			$ports = array(1 => '', 2 => '', 3 => '', 4 => '');
			$ports[$port] = $state;
			$params = array();
			foreach($ports as $port => $state) {
				if(in_array($state, array(self::ON, self::OFF))) {
					$params['cte'.$port] = $state;
				}
			}
			$this->postRequest('http://'.$this->ip, $params);
		}
	}

	function postRequest($url, $fields) {
		$fields_string_array = array();
		foreach($fields as $key=>$value) { $fields_string_array[] = $key.'='.$value; }
		$fields_string = join('&', $fields_string_array);
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
		if($this->debug === true) {
			echo "Calling " . $url . '?' . $fields_string . "\n";
		}

		//close connection
		curl_close($ch);

		// provide html
		return $result;
	}

	function getRequest($url, $fields) {
		$fields_string_array = array();
		foreach($fields as $key=>$value) { $fields_string_array[] = $key.'='.$value; }
		$fields_string = join('&', $fields_string_array);
		//open connection
		$ch = curl_init();

		// configure
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT_MS, 5000);
		//set the url, number of POST vars, POST data
		curl_setopt($ch,CURLOPT_URL, $url . ($fields_string != '' ? '?' . $fields_string : ''));

		//execute post
		$result = curl_exec($ch);
		if($this->debug === true) {
			echo "Calling " . $url . '?' . $fields_string . "\n";
		}

		//close connection
		curl_close($ch);

		// provide html
		return $result;
	}
}
