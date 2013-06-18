<!DOCTYPE html>
<html>
<head>
<title>Hue ON/OFF Switch</title>
</head>
<body>

<?php

include('config.php');
require('hue_lib.php');

$Hue = new Hue($bridgeip, $username);

$huestate = $Hue->getstate(0,1);

if ($huestate['action']['on']) {
$Hue->turnoff(0,1);
} else {
$Hue->turnon(0,1);
}

?>

</body>
</html>