<?php

    require_once('lib/fortissimo.php');
    require_once('lib/EVESystem.php');

    $test = new EVESystem( 30003808 );
    echo("got $test - " . $test->getName() . " <br />");

?>
