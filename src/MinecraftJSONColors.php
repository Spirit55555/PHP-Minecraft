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

namespace Spirit55555\Minecraft;

/**
 * Convert Minecraft JSON text to legacy format.
 *
 * Based on https://minecraft.wiki/w/Text_component_format
 */
class MinecraftJSONColors {
	static private $color_char;
	static private $hex_colors;

	/**
	 * Color names types mapped to legacy codes.
	 *
	 * @var array
	 */
	static private $colors = [
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
	];

	/**
	 * Formatting names mapped to legacy codes.
	 *
	 * @var array
	 */
	static private $formatting = [
		'obfuscated'    => 'k',
		'bold'          => 'l',
		'strikethrough' => 'm',
		'underline'     => 'n',
		'italic'        => 'o',
		'reset'         => 'r'
	];

	/**
	 * Convert Minecraft JSON text to legacy format.
	 *
	 * @param  string|array $json JSON as a string or an array.
	 * @param  string       $color_char The text to prepend all color codes.
	 * @param  bool         $hex_colors Should HEX colors be converted as well? If not, they will be skipped.
	 * @return string
	 */
	public static function convertToLegacy($json, string $color_char = '§', bool $hex_colors = false): string {
		self::$color_char = $color_char;
		self::$hex_colors = $hex_colors;

		if (!empty($json) && is_string($json)) {
			$json = json_decode($json, true);

			//Just return an empty string, if JSON was invalid.
			if (json_last_error() != JSON_ERROR_NONE)
				return '';
		}

		$legacy = '';

		if (is_array($json)) {
			//Reset the formatting to make the components independent.
			$legacy .= self::parseElement($json).self::$color_char.self::$formatting['reset'];
		}

		if (isset($json['extra'])) {
			foreach ($json['extra'] as $component) {
				if (is_string($component))
					$legacy .= $component;
				else
					$legacy .= self::convertToLegacy($component, self::$color_char, self::$hex_colors);
			}
		}

		//If nothing was parsed until here, it's an array of components.
		if (empty($legacy) && is_array($json)) {
			foreach ($json as $item)
				$legacy .= self::convertToLegacy($item, self::$color_char, self::$hex_colors);
		}

		return $legacy;
	}

	/**
	 * Parse an array to a legacy string with color codes.
	 *
	 * @param  array $json
	 * @return string
	 */
	private static function parseElement(array $json): string {
		$legacy = '';

		//Minecraft 1.16+ added support for RGB/HEX colors. Only parse it when enabled.
		if (isset($json['color']) && self::$hex_colors && preg_match('/^#[0-9a-f]{6}$/i', $json['color']))
			$legacy .= self::$color_char.$json['color'];

		if (isset($json['color']) && isset(self::$colors[$json['color']]))
			$legacy .= self::$color_char.self::$colors[$json['color']];

		foreach (self::$formatting as $name => $code) {
			if (isset($json[$name]) && $json[$name])
				$legacy .= self::$color_char.$code;
		}

		if (isset($json['text']))
			$legacy .= $json['text'];

		return $legacy;
	}
}
