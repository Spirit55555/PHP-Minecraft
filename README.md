# PHP-Minecraft

## MinecraftColors.php

Convert Minecraft color codes to HTML/CSS.

### Ussage

```php
<?php
require 'MinecraftColors.php';

$text = '§4Lorem §3§lipsum §rdolor §nsit §c§mamet';

echo MinecraftColors::clean($text);
echo MinecraftColors::convertToHTML($text);
?>
```

## MinecraftVotifier.php

Send Votifier votes to a Minecraft server.

### Ussage

```php
<?php
require 'MinecraftVotifier.php';
$votifier = new MinecraftVotifier('YOUR_PUBLIC_KEY', 'YOUR_SERVER_IP', 'YOUR_VOTIFIER_PORT', 'YOU_SERVICE_NAME');
$votifier->sendVote('MINECRAFT_USERNAME');
?>
```
