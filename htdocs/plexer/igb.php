<?php

    include('lib/plexer.php');
    include('lib/igb.php');
    if (! $ok) { return; }

    # store output here
    $out = "<center><b><font color='#ffff00'>Plexer - The Complex Scheduler</font></b></center><br />";
    $out .= "Welcome to Plexer, the Complex Scheduler, as written by <a href='evemail:Elieza'>Elieza</a>.  This site is ";
    $out .= "designed for use by the Ascendant Frontier alliance and groups we're really tight with.";
    $out .= "<p>Please take a gander using one of the following links.  There's no real wrong way you can go, but ";
    $out .= "I do recommend you stay within this site.  There are monsters outside.</p><ul>";

    $plexes = load_complex_list();
    $links = array();
    foreach ($plexes as $p) {
        $out .= "<li>$p->region: <a href='$_WEB_URL/igb-showcomplex.php?id=$p->plexid'>$p->name</a> (<b>$p->rating/10</b> in <b>$p->system</b>)</li>";
    }

    if ($obj->manager()) {
        $out .= "</ul><p>Administrator's have the following options as well:</p><ul>";
        $out .= "<li><a href='$_WEB_URL/igb-addcomplex.php'>Add New Complex</a></li>";
        $out .= "<li><a href='$_WEB_URL/igb-manage.php'>Manage Access</a></li>";
    }

    $out .= "</ul><p>Thank you for flying the Plexer airways.  If you have any questions, please remember to eve mail me!</p>";

    # all done
    echo("<html><title>Plexer - The Complex Scheduler</title><body>$out</body></html>");

?>

