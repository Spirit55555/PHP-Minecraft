<?php
/*
	Copyright (c) 2025 Anders G. Jørgensen - https://spirit55555.dk

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

/**
 * MinecraftVotifier
 *
 * Class for sending Votifier votes.
 * Supports token (v2) and public key (v1) protocols.
 *
 * More information about the Votifier protocols:
 * https://github.com/NuVotifier/NuVotifier/wiki/Technical-QA
 * @package Spirit55555\Minecraft
 */
class MinecraftVotifier {
	const LEGACY_VOTE_FORMAT = "VOTE\n%s\n%s\n%s\n%d\n";
	const PUBLIC_KEY_FORMAT  = "-----BEGIN PUBLIC KEY-----\n%s\n-----END PUBLIC KEY-----";

	private $stream;
	private $challenge;

	private $host;
	private $port;
	private $token;
	private $public_key;

	/**
	 * Create a new Votifier instance
	 * @param string $host IP address or hostname
	 * @param int $port Port
	 * @param null|string $token Token
	 * @param null|string $public_key Public key
	 * @return void
	 * @throws MinecraftVotifierException
	 */
	public function __construct(string $host, int $port = 8192, ?string $token = '', ?string $public_key = '') {
		$this->host       = $host;
		$this->port       = $port;
		$this->token      = $token;
		$this->public_key = $this->formatPublicKey($public_key);
	}

	public function __destruct() {
		//Close the stream, if it's open
		if (is_resource($this->stream))
			fclose($this->stream);
	}

	public function __get(string $name) {
		return isset($this->$name) ? $this->$name : null;
	}

	public function __set(string $name, mixed $value): void {
		if ($name == 'public_key')
			$this->public_key = $this->formatPublicKey($value);
		else
			$this->$name = $value;
	}

	/**
	 * Parse a header from a server
	 *
	 * This header is only returned by servers that support v2 of the protocol.
	 * @param string $header
	 * @return void
	 */
	private function parseHeader(string $header): void {
		$parts = explode(' ', trim($header));

		if (count($parts) != 3)
			return;

		if ($parts[0] != 'VOTIFIER')
			return;

		if ($parts[1] !== '2')
			return;

		$this->challenge = $parts[2];
	}

	/**
	 * Format a public key, so it can be used with OpenSSL
	 *
	 * This will also check if it is a valid public key.
	 * @param string $public_key
	 * @return string
	 * @throws MinecraftVotifierException
	 */
	private function formatPublicKey(string $public_key): string {
		if (empty($public_key))
			return '';

		$public_key = wordwrap($public_key, 65, "\n", true);
		$public_key = sprintf(self::PUBLIC_KEY_FORMAT, $public_key);

		if (!openssl_pkey_get_public($public_key))
			throw new MinecraftVotifierException('Public key is not valid');

		return $public_key;
	}

	/**
	 * Attempt to send a token based (v2) vote
	 *
	 * The v2 protocol returns an answer from the server and we only consider it a success, if we get an ok and no errors back.
	 * @param MinecraftVotifierVote $vote
	 * @return bool If the vote was successfully sent or not
	 * @throws MinecraftVotifierException
	 */
	private function sendTokenVote(MinecraftVotifierVote $vote): bool {
		if (empty($this->token) || empty($this->challenge))
			return false;

		$payload_data = [
			'challenge' => $this->challenge,
			'serviceName' => $vote->service_name,
			'address' => $vote->address,
			'username' => $vote->username,
			'timestamp' => $vote->timestamp
		];

		if (!empty($vote->uuid))
			$payload_data['uuid'] = $vote->uuid;

		$payload_json = json_encode($payload_data);
		$signature = base64_encode(hash_hmac('sha256', $payload_json, $this->token, true));
		$message_json = json_encode([
			'signature' => $signature,
			'payload' => $payload_json
		]);

		$payload = pack('nn', 0x733a, strlen($message_json)).$message_json;

		if (fwrite($this->stream, $payload) === false)
			throw new MinecraftVotifierException('Could not write to remote host');

		$response = fread($this->stream, 256);

		if (!$response)
			throw new MinecraftVotifierException('Could not read server response');

		$result = json_decode($response);

		if ($result->status != 'ok')
			throw new MinecraftVotifierException('Votifier server error: '.$result->cause.': '.$result->error);

		return true;
	}

	/**
	 * Attempt to send a public key (v1) based vote
	 *
	 * The v1 protocol does not allow us to check if the vote was received or not, so if we can send it, we consider that a success.
	 * @param MinecraftVotifierVote $vote
	 * @return bool If the vote was successfully sent or not
	 */
	private function sendPublickeyVote(MinecraftVotifierVote $vote): bool {
		if (empty($this->public_key))
			return false;

		$legacy_vote = sprintf(self::LEGACY_VOTE_FORMAT, $this->service_name, $vote->username, $vote->address, round($vote->timestamp / 1000));

		if (@openssl_public_encrypt($legacy_vote, $encrypted_data, $this->public_key)) {
			if ($this->stream) {
				if (fwrite($this->stream, $encrypted_data))
					return true;
			}
		}

		return false;
	}

	/**
	 * Send a vote
	 *
	 * If token based (v2) vote fails, it will attempt public key (v1) based vote, if there is a public key defined.
	 *
	 * If no token is defined, it will only attempt public key based vote.
	 * @param MinecraftVotifierVote $vote
	 * @return bool If the vote was successfully sent or not
	 * @throws MinecraftVotifierException
	 */
	public function sendVote(MinecraftVotifierVote $vote): bool {
		if (!$vote->isValid())
			throw new MinecraftVotifierException('Vote is not valid');

		$this->stream = @stream_socket_client('tcp://' . $this->host.':'.$this->port, $errno, $errstr, 3);

		if (!$this->stream)
			throw new MinecraftVotifierException('Could not connect: '.$errstr);

		$header = fread($this->stream, 64);
		$this->parseHeader($header);

		if ($this->sendTokenVote($vote))
			return true;

		else {
			if ($this->sendPublickeyVote($vote))
				return true;
		}

		return false;
	}
}

class MinecraftVotifierException extends \Exception {}
?>
