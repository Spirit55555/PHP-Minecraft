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
use \Spirit55555\Minecraft\MinecraftColors;

final class MinecraftColorsTest extends TestCase {
	public function testClean(): void {
		$text = '§4Lorem §3§lipsum §rdolor &nsit &c&mamet';
		$text_hex = '§#AA0000Lorem §3§lipsum §rdolor &nsit &#FF5555&kamet';

		$this->assertSame('Lorem ipsum dolor sit amet', MinecraftColors::clean($text));
		$this->assertSame('Lorem ipsum dolor sit amet', MinecraftColors::clean($text_hex));
	}

	public function testConvertToHTML(): void {
		$text = '§4Lorem §3§lipsum'."\n".'§rdolor &nsit &c&kamet';
		$text_hex = '§#AA0000Lorem §3§lipsum'."\n".'§rdolor &nsit &#FF5555&kamet';

		$this->assertSame('<span style="color: #AA0000">Lorem </span><span style="color: #00AAAA"><span style="font-weight: bold;">ipsum</span></span>'."\n".'dolor <span style="text-decoration: underline;">sit </span><span style="color: #FF5555"><span class="minecraft-formatted--obfuscated">amet</span></span>', MinecraftColors::convertToHTML($text));
		$this->assertSame('<span style="color: #AA0000">Lorem </span><span style="color: #00AAAA"><span style="font-weight: bold;">ipsum</span></span><br />dolor <span style="text-decoration: underline;">sit </span><span style="color: #FF5555"><span class="minecraft-formatted--obfuscated">amet</span></span>', MinecraftColors::convertToHTML($text, true));
		$this->assertSame('<span class="minecraft-formatted--dark-red">Lorem </span><span class="minecraft-formatted--dark-aqua"><span class="minecraft-formatted--bold">ipsum</span></span><br />dolor <span class="minecraft-formatted--underline">sit </span><span class="minecraft-formatted--red"><span class="minecraft-formatted--obfuscated">amet</span></span>', MinecraftColors::convertToHTML($text, true, true));
		$this->assertSame('<span class="mc-motd--dark-red">Lorem </span><span class="mc-motd--dark-aqua"><span class="mc-motd--bold">ipsum</span></span><br />dolor <span class="mc-motd--underline">sit </span><span class="mc-motd--red"><span class="mc-motd--obfuscated">amet</span></span>', MinecraftColors::convertToHTML($text, true, true, 'mc-motd--'));

		$this->assertSame('<span style="color: #AA0000">Lorem </span><span style="color: #00AAAA"><span style="font-weight: bold;">ipsum</span></span>'."\n".'dolor <span style="text-decoration: underline;">sit </span><span style="color: #FF5555"><span class="minecraft-formatted--obfuscated">amet</span></span>', MinecraftColors::convertToHTML($text_hex));
		$this->assertSame('<span style="color: #AA0000">Lorem </span><span style="color: #00AAAA"><span style="font-weight: bold;">ipsum</span></span><br />dolor <span style="text-decoration: underline;">sit </span><span style="color: #FF5555"><span class="minecraft-formatted--obfuscated">amet</span></span>', MinecraftColors::convertToHTML($text_hex, true));
		$this->assertSame('<span style="color: #AA0000">Lorem </span><span class="minecraft-formatted--dark-aqua"><span class="minecraft-formatted--bold">ipsum</span></span><br />dolor <span class="minecraft-formatted--underline">sit </span><span style="color: #FF5555"><span class="minecraft-formatted--obfuscated">amet</span></span>', MinecraftColors::convertToHTML($text_hex, true, true));
		$this->assertSame('<span style="color: #AA0000">Lorem </span><span class="mc-motd--dark-aqua"><span class="mc-motd--bold">ipsum</span></span><br />dolor <span class="mc-motd--underline">sit </span><span style="color: #FF5555"><span class="mc-motd--obfuscated">amet</span></span>', MinecraftColors::convertToHTML($text_hex, true, true, 'mc-motd--'));
	}

	public function testConvertToHTMLEmptyTags(): void {
		$text = '§7     §4Lorem §3§lipsum§7     ';
		$this->assertSame('<span style="color: #AAAAAA">     </span><span style="color: #AA0000">Lorem </span><span style="color: #00AAAA"><span style="font-weight: bold;">ipsum</span></span><span style="color: #AAAAAA">     </span>', MinecraftColors::convertToHTML($text));
	}

	public function testConvertToMOTD(): void {
		$text = '§4Lorem §3§lipsum §Rdolor &nsit &C&mamet';
		$text_hex = '§#aa0000Lorem §3§lipsum §Rdolor &nsit &#FF5555&mamet';

		$this->assertSame('\u00A74Lorem \u00A73\u00A7lipsum \u00A7rdolor \u00A7nsit \u00A7c\u00A7mamet', MinecraftColors::convertToMOTD($text));
		$this->assertSame('&4Lorem &3&lipsum &rdolor &nsit &c&mamet', MinecraftColors::convertToMOTD($text, '&'));

		$this->assertSame('Lorem \u00A73\u00A7lipsum \u00A7rdolor \u00A7nsit \u00A7mamet', MinecraftColors::convertToMOTD($text_hex, '\u00A7'));
		$this->assertSame('&#AA0000Lorem &3&lipsum &rdolor &nsit &#FF5555&mamet', MinecraftColors::convertToMOTD($text_hex, '&', true));
		$this->assertSame('&x&A&A&0&0&0&0Lorem &3&lipsum &rdolor &nsit &x&F&F&5&5&5&5&mamet', MinecraftColors::convertToMOTD($text_hex, '&', true, true));
	}

	public function testLongHEXFormat(): void {
		$text = '§x§a§A§0§0§0§0Lorem &x&f&F&5&5&5&5ipsum';

		$this->assertSame('Lorem ipsum', MinecraftColors::clean($text));
		$this->assertSame('Lorem ipsum', MinecraftColors::convertToMOTD($text, '&'));
		$this->assertSame('&#AA0000Lorem &#FF5555ipsum', MinecraftColors::convertToMOTD($text, '&', true));
		$this->assertSame('<span style="color: #AA0000">Lorem </span><span style="color: #FF5555">ipsum</span>', MinecraftColors::convertToHTML($text));
	}
}
?>
