<!DOCTYPE html>
<html>
<head>
<title>Hue Dim/Bright Switch</title>
</head>
<body>

<?php

include('config.php');
require('hue_lib.php');

$Hue = new Hue($bridgeip, $username);

$huestate = $Hue->getstate(0,1);

if ($huestate['action']['bri'] >= 40 || !$huestate['action']['on']) {
$Hue->dimlights(0,1);
} else {
$Hue->turnon(0,1);
}

?>

</body>
</html>