[![Latest Stable Version](https://poser.pugx.org/spirit55555/php-minecraft/v)](https://packagist.org/packages/spirit55555/php-minecraft) [![Total Downloads](https://poser.pugx.org/spirit55555/php-minecraft/downloads)](https://packagist.org/packages/spirit55555/php-minecraft) [![License](https://poser.pugx.org/spirit55555/php-minecraft/license)](https://packagist.org/packages/spirit55555/php-minecraft) [![PHP Version Require](https://poser.pugx.org/spirit55555/php-minecraft/require/php)](https://packagist.org/packages/spirit55555/php-minecraft)

# PHP-Minecraft
## Useful PHP classes for Minecraft

### Using Composer?

First require it like this:
```
composer require spirit55555/php-minecraft
```

and then use it like this:
```php
<?php
require 'vendor/autoload.php';
use \Spirit55555\Minecraft\MinecraftColors;

MinecraftColors::clean("test");
?>
```

### Not using Composer?

Just download the files and include them.

## MinecraftColors.php

Convert Minecraft color codes to HTML/CSS. Can also remove the color codes.

### Usage

```php
<?php
require 'vendor/autoload.php';
use \Spirit55555\Minecraft\MinecraftColors;

//Support for § and & signs
$text = '§4Lorem §3§lipsum §rdolor &nsit &c&mamet';

//Convert to HTML with CSS colors
echo MinecraftColors::convertToHTML($text);

//Same as above, but will replace \n with <br />
echo MinecraftColors::convertToHTML($text, true);

//Same as above, but will use CSS classes instead of inline styles
echo MinecraftColors::convertToHTML($text, true, true, 'mc-motd--');

//Will be compatible with the server.properties file
echo MinecraftColors::convertToMOTD($text);

//Will be compatible with BungeeCord's config.yml file
echo MinecraftColors::convertToMOTD($text, '&');

//Will also output RGB/HEX colors, if they exist (&#000000)
//NOTE: Not supported in Vanilla Minecraft
echo MinecraftColors::convertToMOTD($text, '&', true);

//Same as above, but RGB/HEX in a long format (&x&0&0&0&0&0&0)
//NOTE: Not supported in Vanilla Minecraft
echo MinecraftColors::convertToMOTD($text, '&', true, true);

//Remove all color codes
echo MinecraftColors::clean($text);
?>
```

More information about Minecraft colors: https://minecraft.fandom.com/wiki/Formatting_codes#Color_codes

## MinecraftJSONColors.php

Converts  Minecraft JSON (https://wiki.vg/Chat) text to legacy format ('§aHello')

### Usage

```php
<?php
require 'vendor/autoload.php';
use \Spirit55555\Minecraft\MinecraftJSONColors;

$first_component = ['text' => 'first '];
$second_component = ['text' => 'second ', 'color' => 'red'];
$third_component = ['text' => 'third ', 'strikethrough' => true];
$json = ['extra' => [$first_component, $second_component, $third_component]];

echo MinecraftJSONColors::convertToLegacy($json);
?>
```

## MinecraftVotifier.php

Send Votifier votes to a Minecraft server.

### Usage

```php
<?php
require 'vendor/autoload.php';
use \Spirit55555\Minecraft\MinecraftVotifier;

$votifier = new MinecraftVotifier('YOUR_PUBLIC_KEY', 'YOUR_SERVER_IP', 'YOUR_VOTIFIER_PORT', 'YOUR_SERVICE_NAME');
$votifier->sendVote('MINECRAFT_USERNAME');
?>
```

More information about Votifier: https://dev.bukkit.org/projects/votifier
