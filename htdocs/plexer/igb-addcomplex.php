<?php

    include('lib/plexer.php');
    include('lib/igb.php');
    if (! $ok) { return; }

    # manager to be here
    if (! $obj->manager()) {
        return $ft->igberrorpage("Sorry, you must be a manager or higher to use this page.");
    }

    # store output here
    $out = "<center><b><font color='#ffff00'>Plexer - The Complex Scheduler</font></b><br />";
    $out .= "[ <a href='$_WEB_URL/'>Back to Top</a> ]";
    $out .= "</center><br />";

    # now see if they just added one
    if ($_POST['add']) {
        # okay, add it
    } else {
        # nope, so print form
        $out .= "Sorry, this is still under construction.";
    }

    # all done
    echo("<html><title>Plexer - The Complex Scheduler</title><body>$out</body></html>");

?>

