[![Version](https://poser.pugx.org/spirit55555/php-minecraft/version)](https://packagist.org/packages/spirit55555/php-minecraft) [![Build Status](https://api.travis-ci.com/Spirit55555/PHP-Minecraft.svg?branch=master)](https://app.travis-ci.com/github/Spirit55555/PHP-Minecraft) [![License](https://poser.pugx.org/spirit55555/php-minecraft/license)](https://packagist.org/packages/spirit55555/php-minecraft) [![Total Downloads](https://poser.pugx.org/spirit55555/php-minecraft/downloads)](https://packagist.org/packages/spirit55555/php-minecraft)

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

//Remove all color codes
echo MinecraftColors::clean($text);
?>
```

More information about Minecraft colors: http://minecraft.gamepedia.com/index.php?title=Color_codes

## MinecraftJSONColors.php

Converts  Minecraft JSON (http://wiki.vg/Chat) text to legacy format ('§aHello')

### Usage

```php
<?php
require 'vendor/autoload.php';
use \Spirit55555\Minecraft\MinecraftJSONColors;

$first_component = ["text" => "first "];
$second_component = ["text" => "second ", "color" => "red", ""];
$third_component = ["text" => "third ", "strikethrough" => true];
$json = ["extra" => [$first_component, $second_component, $third_component]];

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

More information about Votifier: http://dev.bukkit.org/bukkit-plugins/votifier/
