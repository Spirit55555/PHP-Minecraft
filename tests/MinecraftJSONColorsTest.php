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

use PHPUnit\Framework\TestCase;
use \Spirit55555\Minecraft\MinecraftJSONColors;

final class MinecraftJSONColorsTest extends TestCase {
	public function testConvertToLegacy(): void {
		$json = [];

		$components[] = ['text' => 'second ', 'color' => 'red'];
		$components[] = ['text' => 'third ', 'strikethrough' => true];
		$components[] = ['text' => 'forth ', 'color' => '#AA0000'];

		$json['text'] = 'first ';
		$json['extra'] = $components;

		$this->assertSame('first §r§csecond §r§mthird §rforth §r', MinecraftJSONColors::convertToLegacy($json));
		$this->assertSame('first &r&csecond &r&mthird &rforth &r', MinecraftJSONColors::convertToLegacy($json, '&'));
		$this->assertSame('first §r§csecond §r§mthird §r§#AA0000forth §r', MinecraftJSONColors::convertToLegacy($json, '§', true));
	}

	public function testConvertToLegacyInherit(): void {
		$json = [];

		$forth = ['text' => 'forth ', 'color' => '#AA0000'];
		$third = ['text' => 'third ', 'strikethrough' => true, 'extra' => [$forth, 'fifth ']];
		$second = ['text' => 'second ', 'color' => 'red', 'extra' => [$third]];


		$json['text'] = 'first ';
		$json['extra'] = [$second];

		$this->assertSame('first §r§csecond §r§c§mthird §r§mforth §r§c§mfifth §r', MinecraftJSONColors::convertToLegacy($json));
		$this->assertSame('first &r&csecond &r&c&mthird &r&mforth &r&c&mfifth &r', MinecraftJSONColors::convertToLegacy($json, '&'));
		$this->assertSame('first §r§csecond §r§c§mthird §r§#AA0000§mforth §r§c§mfifth §r', MinecraftJSONColors::convertToLegacy($json, '§', true));
	}
}
?>
