# PHP-Minecraft

## MinecraftColors.php

Convert Minecraft color codes to HTML/CSS. Can also remove the color codes.

### Ussage

```php
<?php
require 'MinecraftColors.php';

//Support for § and & signs
$text = '§4Lorem §3§lipsum §rdolor &nsit &c&mamet';

//Convert to HTML with CSS colors
echo MinecraftColors::convertToHTML($text);

//Same as above, but will replace \n with <br />
echo MinecraftColors::convertToHTML($text, true);

//Will be compatible with the server.properties file
echo MinecraftColors::convertToMOTD($text);

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

$votifier = new MinecraftVotifier('YOUR_PUBLIC_KEY', 'YOUR_SERVER_IP', 'YOUR_VOTIFIER_PORT', 'YOUR_SERVICE_NAME');
$votifier->sendVote('MINECRAFT_USERNAME');
?>
```

More information about Votifier: http://dev.bukkit.org/bukkit-plugins/votifier/
