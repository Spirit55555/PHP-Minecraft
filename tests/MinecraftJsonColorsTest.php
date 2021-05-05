<?php
declare(strict_types=1);
use PHPUnit\Framework\TestCase;
use \Spirit55555\Minecraft\MinecraftJsonColors;

final class MinecraftJsonColorsTest extends TestCase {
	public function testConvertToLegacy(): void {
		$components[] = ["text" => "first "];
		$components[] = ["text" => "second ", "color" => "red", ""];
		$components[] = ["text" => "third ", "strikethrough" => true];
		$components[] = ["text" => "forth ", "color" => '#AA0000'];
		$json = ["extra" => $components];

		$this->assertSame('first §r§csecond §r§mthird §r§#AA0000forth §r', MinecraftJsonColors::convertToLegacy($json));
	}
}
?>
