<?php

    include('lib/plexer.php');
    include('lib/igb.php');
    if (! $ok) { return; }

    # do the stuff
    $id = $_GET['id']+0;
    if ($id <= 0) {
        return $ft->igberrorpage('Sorry, you must provide a valid complex id.');
    }

    # load this complex
    $plex = load_complex($id);
    if (is_null($plex)) {
        return $ft->igberrorpage('Sorry, that complex was not found.');
    }

    # validate their id
    $schedid = $_GET['schedid']+0;
    $run = load_run_schedid($id, $schedid);
    if (! $run) {
        return $ft->igberrorpage('Sorry, that run was not found.');
    }

    # store output here
    $out = "<center><b><font color='#ffff00'>Plexer - The Complex Scheduler</font></b><br />";
    $out .= "<font color='#ff0000'><b>$plex->name</b> in <b>$plex->system</b> rated <b>$plex->rating/10</b></font><br />";
    $out .= "[ <a href='$_WEB_URL/'>Back to Top</a> ] [ <a href='$_WEB_URL/igb-showcomplex.php?id=$id'>Back to Complex</a> ] ";
	$out .= "[ <a href='$_WEB_URL/igb-scheduleinfo.php?id=$id&schedid=$schedid'>Back to Scheduled Run</a> ]";
    $out .= "</center><br />";

    # now give them some commands
    if ($run->corpid != get_corp_id($ft->eve->CorpName)) {
        # same corp, allow them to mark this done
        return $ft->igberrorpage("Sorry, you're not in the right corp for that.");
    }

    # okay they are, so mark it as run
    mark_run_complete($id, $schedid);
    $out .= "<p>Thank you, this run has been completed.</p>";

    # all done
    echo("<html><title>Plexer - The Complex Scheduler</title><body>$out</body></html>");

?>

