<?php
/*
	Copyright (c) 2021 Anders G. Jørgensen - http://spirit55555.dk

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
		$components[] = ["text" => "first "];
		$components[] = ["text" => "second ", "color" => "red", ""];
		$components[] = ["text" => "third ", "strikethrough" => true];
		$components[] = ["text" => "forth ", "color" => '#AA0000'];
		$json = ["extra" => $components];

		$this->assertSame('first §r§csecond §r§mthird §rforth §r', MinecraftJSONColors::convertToLegacy($json));
		$this->assertSame('first &r&csecond &r&mthird &rforth &r', MinecraftJSONColors::convertToLegacy($json, '&'));
		$this->assertSame('first §r§csecond §r§mthird §r§#AA0000forth §r', MinecraftJSONColors::convertToLegacy($json, '§', true));
	}
}
?>
