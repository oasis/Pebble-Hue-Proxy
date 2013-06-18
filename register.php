<!DOCTYPE html>
<html>
<head>
<title>Register Pebble Hue Proxy</title>
</head>
<body>

<?php

include('config.php');
require('hue_lib.php');

$Hue = new Hue($bridgeip, $username);

echo $Hue->register();

?>

</body>
</html>