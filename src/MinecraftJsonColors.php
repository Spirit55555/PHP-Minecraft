<?php
/*
	Copyright (c) 2019 Anders G. Jørgensen - http://spirit55555.dk

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

namespace Spirit55555\Minecraft;

/**
 * Based on http://wiki.vg/Chat
 */
class MinecraftJsonColors {
	const COLOR_CHAR = "§";

	static private $colors = array(
		'black'        => '0',
		'dark_blue'    => '1',
		'dark_green'   => '2',
		'dark_aqua'    => '3',
		'dark_red'     => '4',
		'dark_purple'  => '5',
		'gold'         => '6',
		'gray'         => '7',
		'dark_gray'    => '8',
		'blue'         => '9',
		'green'        => 'a',
		'aqua'         => 'b',
		'red'          => 'c',
		'light_purple' => 'd',
		'yellow'       => 'e',
		'white'        => 'f'
	);

	static private $formatting = array(
		'obfuscated'    => 'k',
		'bold'          => 'l',
		'strikethrough' => 'm',
		'underline'     => 'n',
		'italic'        => 'o',
		'reset'         => 'r'
	);

	public static function convertToLegacy($json) {
		$legacy = '';

		if (isset($json['extra'])) {
			foreach ($json['extra'] as $component) {
				if (is_string($component))
					$legacy .= $component;

				else {
					//reset the formatting to make the components independent
					$legacy .= self::convertToLegacy($component).self::COLOR_CHAR.self::$formatting['reset'];
				}
			}
		}

		$legacy .= self::parseElement($json);

		// If nothing was parsed until here, its an array of components.
		if (empty($legacy) && is_array($json)) {
			foreach ($json as $item)
				$legacy .= self::convertToLegacy($item);
		}

		return $legacy;
	}

	private static function parseElement($json) {
		$legacy = '';

		if (isset($json['color'])) {
			$color = $json['color'];
			if (isset(self::$colors[$color]))
				$legacy .= self::COLOR_CHAR.self::$colors[$color];
		}

		if (isset($json['obfuscated'])) {
			if ($json['obfuscated'])
				$legacy .= self::COLOR_CHAR.self::$formatting['obfuscated'];
		}

		if (isset($json['strikethrough'])) {
			if ($json['strikethrough'])
				$legacy .= self::COLOR_CHAR.self::$formatting['strikethrough'];
		}

		if (isset($json['underlined'])) {
			if ($json['underlined'])
				$legacy .= self::COLOR_CHAR.self::$formatting['underline'];
		}

		if (isset($json['italic'])) {
			if ($json['italic'])
				$legacy .= self::COLOR_CHAR.self::$formatting['italic'];
		}

		if (isset($json['bold'])) {
			if ($json['bold'])
				$legacy .= self::COLOR_CHAR.self::$formatting['bold'];
		}

		if (isset($json['text']))
			$legacy .= $json['text'];

		return $legacy;
	}
}
