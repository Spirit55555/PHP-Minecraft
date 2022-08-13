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

namespace Spirit55555\Minecraft;

/**
 * Convert Minecraft color codes to HTML/CSS. Can also remove the color codes.
 */
class MinecraftColors {
	const REGEX = '/(?:§|&amp;)([0-9a-fklmnor])/i';
	const REGEX_HEX_SHORT = '/(?:§|&amp;)(#[0-9a-f]{6})/i';
	const REGEX_HEX_LONG = '/(?:§|&amp;)x(?:§|&amp;)([0-9a-f])(?:§|&amp;)([0-9a-f])(?:§|&amp;)([0-9a-f])(?:§|&amp;)([0-9a-f])(?:§|&amp;)([0-9a-f])(?:§|&amp;)([0-9a-f])/i';
	const REGEX_ALL = '/(?:§|&amp;)([0-9a-fklmnor]|#[0-9a-f]{6})/i';

	const START_TAG_INLINE_STYLED = '<span style="%s">';
	const START_TAG_WITH_CLASS = '<span class="%s">';
	const CLOSE_TAG = '</span>';

	const CSS_COLOR  = 'color: #';
	const EMPTY_TAGS = '/<[^\/>]*><\/[^>]*>/';
	const LINE_BREAK = '<br />';

	/**
	 * Color codes mapped to HEX colors.
	 *
	 * @var array
	 */
	static private $colors = array(
		'0' => '000000', //Black
		'1' => '0000AA', //Dark Blue
		'2' => '00AA00', //Dark Green
		'3' => '00AAAA', //Dark Aqua
		'4' => 'AA0000', //Dark Red
		'5' => 'AA00AA', //Dark Purple
		'6' => 'FFAA00', //Gold
		'7' => 'AAAAAA', //Gray
		'8' => '555555', //Dark Gray
		'9' => '5555FF', //Blue
		'a' => '55FF55', //Green
		'b' => '55FFFF', //Aqua
		'c' => 'FF5555', //Red
		'd' => 'FF55FF', //Light Purple
		'e' => 'FFFF55', //Yellow
		'f' => 'FFFFFF'  //White
	);

	/**
	 * Formatting codes mapped to CSS style.
	 * Some codes intentionally have no CSS.
	 *
	 * @var array
	 */
	static private $formatting = array(
		'k' => '',                               //Obfuscated
		'l' => 'font-weight: bold;',             //Bold
		'm' => 'text-decoration: line-through;', //Strikethrough
		'n' => 'text-decoration: underline;',    //Underline
		'o' => 'font-style: italic;',            //Italic
		'r' => ''                                //Reset
	);

	/**
	 * Colors and formatting codes mapped to CSS classes.
	 *
	 * @var array
	 */
	static private $css_classnames = array(
		'0' => 'black',
		'1' => 'dark-blue',
		'2' => 'dark-green',
		'3' => 'dark-aqua',
		'4' => 'dark-red',
		'5' => 'dark-purple',
		'6' => 'gold',
		'7' => 'gray',
		'8' => 'dark-gray',
		'9' => 'blue',
		'a' => 'green',
		'b' => 'aqua',
		'c' => 'red',
		'd' => 'light-purple',
		'e' => 'yellow',
		'f' => 'white',
		'k' => 'obfuscated',
		'l' => 'bold',
		'm' => 'line-strikethrough',
		'n' => 'underline',
		'o' => 'italic'
	);

	/**
	 * Encode text in UTF-8.
	 *
	 * @param  mixed $text
	 * @return string
	 */
	static private function UFT8Encode(string $text): string {
		//Encode the text in UTF-8, but only if it's not already.
		if (mb_detect_encoding($text) != 'UTF-8')
			$text = mb_convert_encoding($text, 'UTF-8', mb_detect_encoding($text));

		return $text;
	}

	/**
	 * Convert the long HEX format to the short.
	 * Example of long HEX format: §x§a§A§0§0§0§0
	 *
	 * @param string $text
	 * @return string
	 */
	static private function convertLongHEXtoShortHEX(string $text): string {
		if (preg_match_all(self::REGEX_HEX_LONG, $text, $matches,  PREG_SET_ORDER)) {
			foreach ($matches as $match) {
				$hex_color = '§#'.$match[1].$match[2].$match[3].$match[4].$match[5].$match[6];
				$text = str_replace($match[0], $hex_color, $text);
			}
		}

		return $text;
	}

	/**
	 * Clean a string from all colors and formatting codes.
	 *
	 * @param  string $text
	 * @return string
	 */
	static public function clean(string $text): string {
		$text = self::UFT8Encode($text);
		$text = htmlspecialchars($text);

		$text = preg_replace(self::REGEX_HEX_LONG, '', $text);

		return preg_replace(self::REGEX_ALL, '', $text);
	}

	/**
	 * Convert a string to a MOTD format.
	 *
	 * @param  string $text
	 * @param  string $sign The text to prepend all color codes.
	 * @param  bool   $hex_colors Should HEX colors be converted as well? If not, they will be removed.
	 * @param  bool   $hex_long_format Should HEX colors be returned in the long format?
	 * @return string
	 */
	static public function convertToMOTD(string $text, string $sign = '\u00A7', bool $hex_colors = false, bool $hex_long_format = false): string {
		$text = self::UFT8Encode($text);
		$text = str_replace("&", "&amp;", $text);

		if ($hex_colors) {
			$text = self::convertLongHEXtoShortHEX($text);

			$text = preg_replace_callback(
				self::REGEX,
				function($matches) use ($sign) {
					return $sign.strtolower($matches[1]);
				},
				$text
			);

			$text = preg_replace_callback(
				self::REGEX_HEX_SHORT,
				function($matches) use ($sign, $hex_long_format) {
					if ($hex_long_format) {
						$color = ltrim(strtoupper($matches[1]), '#');
						$chars = str_split($color);

						return $sign.'x'.$sign.$chars[0].$sign.$chars[1].$sign.$chars[2].$sign.$chars[3].$sign.$chars[4].$sign.$chars[5];
					}

					else
						return $sign.strtoupper($matches[1]);
				},
				$text
			);
		}

		else {
			$text = preg_replace(self::REGEX_HEX_SHORT, '', $text);
			$text = preg_replace(self::REGEX_HEX_LONG, '', $text);

			$text = preg_replace_callback(
				self::REGEX,
				function($matches) use ($sign) {
					return $sign.strtolower($matches[1]);
				},
				$text
			);
		}

		$text = str_replace("\n", '\n', $text);
		$text = str_replace("&amp;", "&", $text);

		return $text;
	}

	/**
	 * Convert a string to HTML.
	 *
	 * @param  string $text
	 * @param  bool   $line_break_element Should new lines be converted to br tags?
	 * @param  bool   $css_classes Should CSS classes be used instead of inline styes?
	 * @param  string $css_prefix The prefix for CSS classes.
	 * @return string
	 */
	static public function convertToHTML(string $text, bool $line_break_element = false, bool $css_classes = false, string $css_prefix = 'minecraft-formatted--'): string {
		$text = self::UFT8Encode($text);
		$text = htmlspecialchars($text);

		$text = self::convertLongHEXtoShortHEX($text);

		preg_match_all(self::REGEX_ALL, $text, $offsets);

		$colors      = $offsets[0]; //This is what we are going to replace with HTML.
		$color_codes = $offsets[1]; //This is the color numbers/characters only.

		//No colors? Just return the text.
		if (empty($colors))
			return $text;

		$open_tags = 0;

		foreach ($colors as $index => $color) {
			$color_code = strtolower($color_codes[$index]);

			$html = '';

			$is_reset = $color_code === 'r';
			$is_color = isset(self::$colors[$color_code]);
			$is_hex = strlen($color_code) === 7; //#RRGGBB

			if ($is_reset || $is_color || $is_hex) {
				// New colors or the reset char: reset all other colors and formatting.
				if ($open_tags != 0) {
					$html = str_repeat(self::CLOSE_TAG, $open_tags);
					$open_tags = 0;
				}
			}

			if ($css_classes && !$is_reset) {
				//No reason to give HEX colors a CSS class.
				if ($is_hex) {
					$html .= sprintf(self::START_TAG_INLINE_STYLED, self::CSS_COLOR.ltrim(strtoupper($color_code), '#'));
					$open_tags++;
				}

				else {
					$css_classname = $css_prefix.self::$css_classnames[$color_code];
					$html .= sprintf(self::START_TAG_WITH_CLASS, $css_classname);
					$open_tags++;
				}
			}

			else {
				if ($is_color) {
					$html .= sprintf(self::START_TAG_INLINE_STYLED, self::CSS_COLOR.self::$colors[$color_code]);
					$open_tags++;
				}

				else if ($is_hex) {
					$html .= sprintf(self::START_TAG_INLINE_STYLED, self::CSS_COLOR.ltrim(strtoupper($color_code), '#'));
					$open_tags++;
				}

				//Special case for obfuscated, always add a CSS class for this.
				else if ($color_code === 'k') {
					$css_classname = $css_prefix.self::$css_classnames[$color_code];
					$html .= sprintf(self::START_TAG_WITH_CLASS, $css_classname);
					$open_tags++;
				}

				else if (!$is_reset) {
					$html .= sprintf(self::START_TAG_INLINE_STYLED, self::$formatting[$color_code]);
					$open_tags++;
				}
			}

			//Replace the color with the HTML code. We use preg_replace because of the limit parameter.
			$text = preg_replace('/'.$color.'/', $html, $text, 1);
		}

		//Still open tags? Close them!
		if ($open_tags != 0)
			$text = $text.str_repeat(self::CLOSE_TAG, $open_tags);

		//Move newline endings outside elements.
		while (strpos($text, "\n".self::CLOSE_TAG) !== false)
			$text = str_replace("\n".self::CLOSE_TAG, self::CLOSE_TAG."\n", $text);

		//Replace \n with <br />
		if ($line_break_element)
			$text = str_replace(array('\n', "\n"), self::LINE_BREAK, $text);

		//Return the text without empty HTML tags. Only to clean up bad color formatting from the user.
		return preg_replace(self::EMPTY_TAGS, '', $text);
	}
}
?>
