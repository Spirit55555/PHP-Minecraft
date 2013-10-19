<?php
require 'MinecraftColors.php';

$text = '§4Lorem §3§lipsum §rdolor §nsit §c§mamet';
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8" />
<title>PHP-MinecraftColors</title>
</head>
<body>
	<h1>PHP-MinecraftColors</h1>
	<h2>Before:</h2>
	<p><?php echo $text; ?></p>
	<h2>MinecraftColors::clean($text):</h2>
	<p><?php echo MinecraftColors::clean($text); ?></p>
	<h2>MinecraftColors::convertToHTML($text):</h2>
	<p><?php echo MinecraftColors::convertToHTML($text); ?></p>
</body>
</html>