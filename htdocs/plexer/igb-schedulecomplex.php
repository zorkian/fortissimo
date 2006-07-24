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
    $dayid = $_GET['dayid']+0;
    $slotid = $_GET['slotid']+0;
    $time = slot_to_date($dayid);
    if (is_null($time) || $time <= 0 || $slotid <= 0) {
        return $ft->igberrorpage('Sorry, that does not appear to be a valid dayid and/or slotid.');
    }

    # get run information
    $run = load_run($id, $dayid, $slotid);
    if ($run) {
        return $ft->igberrorpage('Sorry, that run is already scheduled by <font color="#ff0000">' . get_corp_name($run->corpid) . '</font>.');
    }

    # see if they are allowed to reserve spots
    # FIXME: implement
    if (0) {
        return $ft->igberrorpage('Sorry, you must be an accountant or director in your corporation in order to schedule a run.');
    }

    # store output here
    $out = "<center><b><font color='#ffff00'>Plexer - The Complex Scheduler</font></b><br />";
    $out .= "<font color='#ff0000'><b>$plex->name</b> in <b>$plex->system</b> rated <b>$plex->rating/10</b></font><br />";
    $out .= "[ <a href='$_WEB_URL/'>Back to Top</a> ] [ <a href='$_WEB_URL/igb-showcomplex.php?id=$id'>Back to Complex</a> ]";
    $out .= "</center><br />";

    # if they're trying to confirm
    if ($_GET['confirm'] == 1) {
        # okay, set the confirmation
        $run = submit_schedule($id, $dayid, $slotid, get_corp_id($ft->eve->CorpName), get_pilot_id($ft->eve->CharName));
        $out .= "Your reservation has been confirmed.  <b>Good luck!</b>";

    } else {
        # so they want to reserve it eh...
        $out .= "Okay, so you want to run a complex?  Well, from the looks of it, the run you've chosen is not ";
        $out .= "already reserved for someone.  That's good for you, means you should be able to get in.";
        $out .= "<p>Please review the information below and confirm that you intend to run this complex.  <b>You are ";
        $out .= "responsible for ensuring your corporation/group runs the complex.  If you reserve slots and do not ";
        $out .= "use them, we will hunt you down and barbeque you.  :-)</p>";

        # now dump the information
        $info = array(
            "Complex" => "<a href='$_WEB_URL/igb-showcomplex.php?id=$id'>$plex->name</a> (" . $plex->rating . "/10)",
            "System" => $plex->system . " (" . $plex->region . ")",
            "a" => "-",
            "Date" => stddate(slot_to_date($dayid)),
            "Name" => get_complex_slot_name($slotid),
            "Description" => get_complex_slot_desc($slotid),
            "b" => "-",
            "Reserve for corp" => $ft->eve->CorpName,
            "Reserve by pilot" => $ft->eve->CharName,
        );
        foreach ($info as $k => $v) {
            if ($v == "-") {
                $out .= "<br />";
                continue;
            }
            $out .= " &bull; <font color='#ffff00'>$k:</font> $v<br />";
        }

        # give them a button to reserve it
        $out .= "<p>[ <a href='$_WEB_URL/igb-schedulecomplex.php?id=$id&dayid=$dayid&slotid=$slotid&confirm=1'>Confirm Reservation</a> ]</p>";
    }

    # all done
    echo("<html><title>Plexer - The Complex Scheduler</title><body>$out</body></html>");

?>

