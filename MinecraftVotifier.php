<?php
/*
    Copyright (c) 2016 Anders G. Jørgensen - http://spirit55555.dk

    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/

class MinecraftVotifier {
	const VOTE_FORMAT       = "VOTE\n%s\n%s\n%s\n%d\n";
	const PUBLIC_KEY_FORMAT = "-----BEGIN PUBLIC KEY-----\n%s\n-----END PUBLIC KEY-----";

	private $public_key;
	private $server_ip;
	private $port;
	private $service_name;

	public function __construct($public_key = null, $server_ip = null, $port = 8192, $service_name = null) {
		$this->public_key   = $this->formatPublicKey($public_key);
		$this->server_ip    = $server_ip;
		$this->port         = $port;
		$this->service_name = $service_name;
	}

	public function __get($name) {
		return isset($this->$name) ? $this->$name : null;
	}

	public function __set($name, $value) {
		if ($name == 'public_key')
			$this->public_key = $this->formatPublicKey($value);
		else
			$this->$name = $value;
	}

	private function formatPublicKey($public_key) {
		$public_key = wordwrap($public_key, 65, "\n", true);
		$public_key = sprintf(self::PUBLIC_KEY_FORMAT, $public_key);

		return $public_key;
	}

	public function sendVote($username) {
		if(php_sapi_name() !== 'cli') {
			// Use Client Address BEHIND Proxy if it is a transparent proxy...
			$address = isset($_SERVER["HTTP_X_FORWARDED_FOR"]) ? $_SERVER["HTTP_X_FORWARDED_FOR"] : $_SERVER['REMOTE_ADDR'];
		} else {
			// Script is run by CLI, use our own Name
			$address = $_SERVER["HOST_NAME"];
		}
		
		$vote = sprintf(self::VOTE_FORMAT, $this->service_name, $username, $address, time());

		openssl_public_encrypt($vote, $data, $this->public_key);

		$socket = @fsockopen($this->server_ip, $this->port);

		if ($socket) {
			if (fwrite($socket, $data)) {
				fclose($socket);
				return true;
			}
		}

		return false;
	}
}
?>
