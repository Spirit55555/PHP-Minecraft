<?php
declare(strict_types=1);
use PHPUnit\Framework\TestCase;
use \Spirit55555\Minecraft\MinecraftJsonColors;

final class MinecraftJsonColorsTest extends TestCase {
	public function testConvertToLegacy(): void {
		$first_component = ["text" => "first "];
		$second_component = ["text" => "second ", "color" => "red", ""];
		$third_component = ["text" => "third ", "strikethrough" => true];
		$json = ["extra" => [$first_component, $second_component, $third_component]];

		$this->assertSame('first §r§csecond §r§mthird §r', MinecraftJsonColors::convertToLegacy($json));
	}
}
?>
