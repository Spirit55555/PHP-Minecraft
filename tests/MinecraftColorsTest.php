<?php
declare(strict_types=1);
use PHPUnit\Framework\TestCase;
use \Spirit55555\Minecraft\MinecraftColors;

final class MinecraftColorsTest extends TestCase {
	public function testClean(): void {
		$text = '§4Lorem §3§lipsum §rdolor &nsit &c&mamet';

		$this->assertSame('Lorem ipsum dolor sit amet', MinecraftColors::clean($text));
	}

	public function testConvertToHTML(): void {
		$text = "§4Lorem §3§lipsum\n§rdolor &nsit &c&mamet";

		$this->assertSame('<span style="color: #AA0000">Lorem </span><span style="color: #00AAAA"><span style="font-weight: bold;">ipsum'."\n".'</span></span>dolor <span style="text-decoration: underline;">sit </span><span style="color: #FF5555"><span style="text-decoration: line-through;">amet</span></span>', MinecraftColors::convertToHTML($text));
		$this->assertSame('<span style="color: #AA0000">Lorem </span><span style="color: #00AAAA"><span style="font-weight: bold;">ipsum<br /></span></span>dolor <span style="text-decoration: underline;">sit </span><span style="color: #FF5555"><span style="text-decoration: line-through;">amet</span></span>', MinecraftColors::convertToHTML($text, true));
		$this->assertSame('<span class="minecraft-formatted--dark-red">Lorem </span><span class="minecraft-formatted--dark-aqua"><span class="minecraft-formatted--bold">ipsum<br /></span></span>dolor <span class="minecraft-formatted--underline">sit </span><span class="minecraft-formatted--red"><span class="minecraft-formatted--line-strikethrough">amet</span></span>', MinecraftColors::convertToHTML($text, true, true));
		$this->assertSame('<span class="mc-motd--dark-red">Lorem </span><span class="mc-motd--dark-aqua"><span class="mc-motd--bold">ipsum<br /></span></span>dolor <span class="mc-motd--underline">sit </span><span class="mc-motd--red"><span class="mc-motd--line-strikethrough">amet</span></span>', MinecraftColors::convertToHTML($text, true, true, 'mc-motd--'));
	}

	public function testConvertToMOTD(): void {
		$text = '§4Lorem §3§lipsum §rdolor &nsit &c&mamet';

		$this->assertSame('\u00A74Lorem \u00A73\u00A7lipsum \u00A7rdolor \u00A7nsit \u00A7c\u00A7mamet', MinecraftColors::convertToMOTD($text));
		$this->assertSame('&4Lorem &3&lipsum &rdolor &nsit &c&mamet', MinecraftColors::convertToMOTD($text, '&'));
	}
}
?>
