<?php
declare(strict_types=1);
use PHPUnit\Framework\TestCase;
use \Spirit55555\Minecraft\MinecraftColors;

final class MinecraftColorsTest extends TestCase {
	public function testClean(): void {
		$text = '§4Lorem §3§lipsum §rdolor &nsit &c&mamet';
		$text_hex = "§#AA0000Lorem §3§lipsum §rdolor &nsit &#FF5555&kamet";

		$this->assertSame('Lorem ipsum dolor sit amet', MinecraftColors::clean($text));
		$this->assertSame('Lorem ipsum dolor sit amet', MinecraftColors::clean($text_hex));
	}

	public function testConvertToHTML(): void {
		$text = "§4Lorem §3§lipsum\n§rdolor &nsit &c&kamet";
		$text_hex = "§#AA0000Lorem §3§lipsum\n§rdolor &nsit &#FF5555&kamet";

		$this->assertSame('<span style="color: #AA0000">Lorem </span><span style="color: #00AAAA"><span style="font-weight: bold;">ipsum</span></span>'."\n".'dolor <span style="text-decoration: underline;">sit </span><span style="color: #FF5555"><span class="minecraft-formatted--obfuscated">amet</span></span>', MinecraftColors::convertToHTML($text));
		$this->assertSame('<span style="color: #AA0000">Lorem </span><span style="color: #00AAAA"><span style="font-weight: bold;">ipsum</span></span><br />dolor <span style="text-decoration: underline;">sit </span><span style="color: #FF5555"><span class="minecraft-formatted--obfuscated">amet</span></span>', MinecraftColors::convertToHTML($text, true));
		$this->assertSame('<span class="minecraft-formatted--dark-red">Lorem </span><span class="minecraft-formatted--dark-aqua"><span class="minecraft-formatted--bold">ipsum</span></span><br />dolor <span class="minecraft-formatted--underline">sit </span><span class="minecraft-formatted--red"><span class="minecraft-formatted--obfuscated">amet</span></span>', MinecraftColors::convertToHTML($text, true, true));
		$this->assertSame('<span class="mc-motd--dark-red">Lorem </span><span class="mc-motd--dark-aqua"><span class="mc-motd--bold">ipsum</span></span><br />dolor <span class="mc-motd--underline">sit </span><span class="mc-motd--red"><span class="mc-motd--obfuscated">amet</span></span>', MinecraftColors::convertToHTML($text, true, true, 'mc-motd--'));

		$this->assertSame('<span style="color: #AA0000">Lorem </span><span style="color: #00AAAA"><span style="font-weight: bold;">ipsum</span></span>'."\n".'dolor <span style="text-decoration: underline;">sit </span><span style="color: #FF5555"><span class="minecraft-formatted--obfuscated">amet</span></span>', MinecraftColors::convertToHTML($text_hex));
		$this->assertSame('<span style="color: #AA0000">Lorem </span><span style="color: #00AAAA"><span style="font-weight: bold;">ipsum</span></span><br />dolor <span style="text-decoration: underline;">sit </span><span style="color: #FF5555"><span class="minecraft-formatted--obfuscated">amet</span></span>', MinecraftColors::convertToHTML($text_hex, true));
		$this->assertSame('<span style="color: #AA0000">Lorem </span><span class="minecraft-formatted--dark-aqua"><span class="minecraft-formatted--bold">ipsum</span></span><br />dolor <span class="minecraft-formatted--underline">sit </span><span style="color: #FF5555"><span class="minecraft-formatted--obfuscated">amet</span></span>', MinecraftColors::convertToHTML($text_hex, true, true));
		$this->assertSame('<span style="color: #AA0000">Lorem </span><span class="mc-motd--dark-aqua"><span class="mc-motd--bold">ipsum</span></span><br />dolor <span class="mc-motd--underline">sit </span><span style="color: #FF5555"><span class="mc-motd--obfuscated">amet</span></span>', MinecraftColors::convertToHTML($text_hex, true, true, 'mc-motd--'));
	}

	public function testConvertToMOTD(): void {
		$text = '§4Lorem §3§lipsum §rdolor &nsit &c&mamet';
		$text_hex = "§#AA0000Lorem §3§lipsum\n§rdolor &nsit &#FF5555&kamet";

		$this->assertSame('\u00A74Lorem \u00A73\u00A7lipsum \u00A7rdolor \u00A7nsit \u00A7c\u00A7mamet', MinecraftColors::convertToMOTD($text));
		$this->assertSame('&4Lorem &3&lipsum &rdolor &nsit &c&mamet', MinecraftColors::convertToMOTD($text, '&'));

		$this->assertSame('Lorem \u00A73\u00A7lipsum \u00A7rdolor \u00A7nsit \u00A7mamet', MinecraftColors::convertToMOTD($text_hex, false));
		$this->assertSame('&#AA0000Lorem &3&lipsum &rdolor &nsit &#FF5555&mamet', MinecraftColors::convertToMOTD($text_hex, '&'));
	}
}
?>
