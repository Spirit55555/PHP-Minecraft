# PHP-Minecraft

## MinecraftColors.php

Convert Minecraft color codes to HTML/CSS. Can also remove the color codes.

### Ussage

```php
<?php
require 'MinecraftColors.php';

$text = '§4Lorem §3§lipsum §rdolor §nsit §c§mamet';

echo MinecraftColors::convertToHTML($text);
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
