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

use PHPUnit\Framework\TestCase;
use Spirit55555\Minecraft\MinecraftVotifierVote;
use Spirit55555\Minecraft\MinecraftVotifierVoteException;

final class MinecraftVotifierVoteTest extends TestCase {
	public function testValidVote(): void {
		$vote = new MinecraftVotifierVote('Testing', '127.0.0.1', 'Spirit55555', 'f6792ad3-cbb4-4596-8296-749ee4158f97');

		$this->assertTrue($vote->isValid());
	}

	public function testValidVoteWithIPv6Address(): void {
		$vote = new MinecraftVotifierVote('Testing', '2001:db8:3333:4444:5555:6666:7777:8888', 'Spirit55555');

		$this->assertTrue($vote->isValid());
	}

	public function testInvalidIPv4(): void {
		$this->expectException(MinecraftVotifierVoteException::class);

		new MinecraftVotifierVote('Testing', '127.0.0', 'Spirit55555');
	}

	public function testInvalidIPv6(): void {
		$this->expectException(MinecraftVotifierVoteException::class);

		new MinecraftVotifierVote('Testing', '2001:db8:3333:4444:5555:6666:7777', 'Spirit55555');
	}

	public function testInvalidUsername(): void {
		$this->expectException(MinecraftVotifierVoteException::class);

		new MinecraftVotifierVote('Testing', '127.0.0.1', 'Spirit@55555');
	}

	public function testInvalidUUID(): void {
		$this->expectException(MinecraftVotifierVoteException::class);

		new MinecraftVotifierVote('Testing', '127.0.0.1', 'Spirit@55555', 'f6792ad3-cbb4-4596-8296');
	}

	public function testInvalidTimestamp(): void {
		$this->expectException(MinecraftVotifierVoteException::class);

		new MinecraftVotifierVote('Testing', '127.0.0.1', 'Spirit55555', 'f6792ad3-cbb4-4596-8296-749ee4158f97', -1);
	}
}
?>
