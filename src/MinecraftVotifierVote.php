<?php
/*
	Copyright (c) 2024 Anders G. Jørgensen - https://spirit55555.dk

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

declare(strict_types=1);

namespace Spirit55555\Minecraft;

class MinecraftVotifierVote {
	const USERNAME_FORMAT   = '/^[a-zA-Z0-9_]{3,16}$/';
	const UUID_FORMAT_FULL  = '/^[0-9A-F]{8}-[0-9A-F]{4}-[4][0-9A-F]{3}-[89AB][0-9A-F]{3}-[0-9A-F]{12}$/i';
	const UUID_FORMAT_SHORT = '/^[0-9A-F]{8}[0-9A-F]{4}[4][0-9A-F]{3}[89AB][0-9A-F]{3}[0-9A-F]{12}$/i';

	private $service_name;
	private $address;
	private $username;
	private $uuid;
	private $timestamp;

	public function __construct(string $service_name, string $address, string $username, ?string $uuid = '', ?float $timestamp = 0) {
		$this->service_name = $service_name;
		$this->address      = $this->validateAddress($address);
		$this->username     = $this->validateUsername($username);
		$this->uuid         = $this->validateUUID($uuid);
		$this->timestamp    = $this->validateTimestamp($timestamp);
	}

	public function __get(string $name) {
		return isset($this->$name) ? $this->$name : null;
	}

	public function __set(string $name, mixed $value) {
		switch ($name) {
			case 'address':
				$this->address = $this->validateAddress($value);
				break;
			case 'username':
				$this->username = $this->validateUsername($value);
				break;
			case 'uuid':
				$this->uuid = $this->validateUUID($value);
				break;
			case 'timestamp':
				$this->timestamp = $this->validateTimestamp($value);
				break;
			default:
				$this->$name = $value;
		}
	}

	public function isValid(): bool {
		if (!empty($this->service_name) && !empty($this->address) && !empty($this->username))
			return true;

		return false;
	}

	private function validateAddress(string $address): string {
		if (filter_var($address, FILTER_VALIDATE_IP) === false)
			throw new MinecraftVotifierVoteException('Address is not a valid IP address');

		return $address;
	}

	private function validateUsername(string $username): string {
		if (!preg_match(self::USERNAME_FORMAT, $username))
			throw new MinecraftVotifierVoteException('Username is not valid');

		return $username;
	}

	private function validateUUID(string $uuid): string {
		//Convert short UUID to full version
		if (preg_match(self::UUID_FORMAT_SHORT, $uuid)) {
			$parts[] = substr($uuid, 0, 8);
			$parts[] = '-'.substr($uuid, 8, 4);
			$parts[] = '-'.substr($uuid, 12, 4);
			$parts[] = '-'.substr($uuid, 16, 4);
			$parts[] = '-'.substr($uuid, 20);

			$uuid = implode($parts);
		}

		if (!preg_match(self::UUID_FORMAT_FULL, $uuid))
			throw new MinecraftVotifierVoteException('UUID is not valid');

		return $uuid;
	}

	private function validateTimestamp(float $timestamp): float {
		if (!is_float($timestamp))
			throw new MinecraftVotifierVoteException('Timestamp is not valid');

		if ($timestamp === 0)
			return round(microtime(true) / 1000);

		return $timestamp;
	}
}

class MinecraftVotifierVoteException extends \Exception {}
?>
