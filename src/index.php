<?php

include 'classes.php';

$nakki = new Sheet();

$paska = $nakki->getAttributes();

foreach ($paska as $kulli) {
    echo $kulli->getName();
            echo $kulli->getPoints();
}
?>