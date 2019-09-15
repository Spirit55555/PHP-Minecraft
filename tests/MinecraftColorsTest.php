<?php
declare(strict_types=1);
use PHPUnit\Framework\TestCase;
use \Spirit55555\Minecraft\MinecraftColors;

final class MinecraftColorsTest extends TestCase {
	public function testclean(): void {
		$text = '§4Lorem §3§lipsum §rdolor &nsit &c&mamet';
		$this->assertSame('Lorem ipsum dolor sit amet', MinecraftColors::clean($text));
	}
}
?>
