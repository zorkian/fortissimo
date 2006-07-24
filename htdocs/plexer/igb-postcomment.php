<?php

    include('lib/plexer.php');
    include('lib/igb.php');
    if (! $ok) { return; }

    # do the stuff
    $id = $_POST['id']+0;
    if ($id <= 0) {
        return $ft->igberrorpage('Sorry, you must provide a valid complex id.');
    }
    $schedid = $_POST['schedid']+0;
    if ($schedid <= 0) {
        return $ft->igberrorpage('Sorry, you must provide a valid schedule id.');
    }

    # load this complex
    $plex = load_complex($id);
    if (is_null($plex)) {
        return $ft->igberrorpage('Sorry, that complex was not found.');
    }

    # load this complex
    $sched = load_run_schedid($id, $schedid);
    if (is_null($sched)) {
        return $ft->igberrorpage('Sorry, that complex scheduled run was not found.');
    }

	# no comment?
	$comment = $_POST['comment'];
	if (! $comment) {
		return $ft->igberrorpage('Sorry, you must type something in the comment box.');
	}

    # see if they are allowed to make comments
    # FIXME: implement
    if (0) {
        return $ft->igberrorpage('Sorry, you must be an accountant or director in your corporation in order to schedule a run.');
    }

	# add the comment
	add_comment($id, $schedid, $obj->id, $comment);

    # store output here
    $out = "<center><b><font color='#ffff00'>Plexer - The Complex Scheduler</font></b><br />";
    $out .= "<font color='#ff0000'><b>$plex->name</b> in <b>$plex->system</b> rated <b>$plex->rating/10</b></font><br />";
    $out .= "[ <a href='$_WEB_URL/'>Back to Top</a> ] [ <a href='$_WEB_URL/igb-showcomplex.php?id=$id'>Back to Complex</a> ] ";
	$out .= "[ <a href='$_WEB_URL/igb-scheduleinfo.php?id=$id&schedid=$schedid'>Back to Scheduled Run</a> ]";
    $out .= "</center><br />";
	$out .= "<p>Your comment has been recorded.  Use one of the links above to continue.</p>";

    # all done
    echo("<html><title>Plexer - The Complex Scheduler</title><body>$out</body></html>");

?>

