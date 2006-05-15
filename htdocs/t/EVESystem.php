<?php

    require_once('lib/fortissimo.php');
    require_once('lib/EVESystem.php');

    $test = new EVESystem( 30003808 );
    echo("got $test - " . $test->getName() . " - " . $test->getConstellationName() . " - " . $test->getRegionName() . " <br />");
    echo("okay new test - " . EVESystem::getName( 30003808 ) . "<br />");
    echo("if those are both Grispire, you're golden <tt>:)</tt><br />");

?>
