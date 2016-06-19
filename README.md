# PHP-Minecraft
## Useful PHP classes for Minecraft

###Using Composer?

First require it like this:
```
composer require spirit55555/php-minecraft
```

and then use it like this:
```php
use \Spirit55555\Minecraft\MinecraftColors;

class test
{
    public function functionName($param)
    {
        MinecraftColors::clean("test");
    }
}
```

###Not using Composer?

Just download the files and include them.

## MinecraftColors.php

Convert Minecraft color codes to HTML/CSS. Can also remove the color codes.

### Ussage

```php
<?php
require 'MinecraftColors.php';

use \Spirit55555\Minecraft\MinecraftColors;

//Support for § and & signs
$text = '§4Lorem §3§lipsum §rdolor &nsit &c&mamet';

//Convert to HTML with CSS colors
echo MinecraftColors::convertToHTML($text);

//Same as above, but will replace \n with <br />
echo MinecraftColors::convertToHTML($text, true);

//Will be compatible with the server.properties file
echo MinecraftColors::convertToMOTD($text);

//Will be compatible with BungeeCord's config.yml file
echo MinecraftColors::convertToMOTD($text, '&');

//Removed all color codes
echo MinecraftColors::clean($text);
?>
```

More information about Minecraft colors: http://minecraft.gamepedia.com/index.php?title=Color_codes

## MinecraftVotifier.php

Send Votifier votes to a Minecraft server.

### Ussage

```php
<?php
require 'MinecraftVotifier.php';

use \Spirit55555\Minecraft\MinecraftVotifier;

$votifier = new MinecraftVotifier('YOUR_PUBLIC_KEY', 'YOUR_SERVER_IP', 'YOUR_VOTIFIER_PORT', 'YOUR_SERVICE_NAME');
$votifier->sendVote('MINECRAFT_USERNAME');
?>
```

## MinecraftJsonColors.php

Converts the minecraft json (http://wiki.vg/Chat) text to legacy format ('§aHello')

### Ussage

```php
<?php
require 'MinecraftJsonColors.php';

use \Spirit55555\Minecraft\MinecraftJsonColors;

$first_component = ["text" => "first "];
$second_component = ["text" => "second ", "color" => "red", ""];
$third_component = ["text" => "third ", "strikethrough" => true];
$json = ["extra" => [$first_component, $second_component, $third_component]];

echo MinecraftJsonColors::convertToLegacy($json);
?>
```

More information about Votifier: http://dev.bukkit.org/bukkit-plugins/votifier/
