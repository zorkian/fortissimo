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
    $out .= "[ <a href='$_WEB_URL/'>Back to Top</a> ] [ <a href='$_WEB_URL/igb-showcomplex.php?id=$id'>Back to Complex</a> ]";
    $out .= "</center><br />";

    # dump the information
    $info = array(
        "Complex" => "<a href='$_WEB_URL/igb-showcomplex.php?id=$id'>$plex->name</a> (" . $plex->rating . "/10)",
        "System" => $plex->system . " (" . $plex->region . ")",
        "1" => "-",
        "Date" => stddate(slot_to_date($run->dayid)),
        "Name" => get_complex_slot_name($id, $run->slotid),
        "Description" => get_complex_slot_desc($id, $run->slotid),
        "2" => "-",
        "Reserved for corp" => get_corp_name($run->corpid),
        "Reserved by pilot" => get_pilot_name($run->pilotid),
        "3" => "-",
        "Was ran at" => (is_null($run->ranattime) ? "not run yet" : stddate($run->ranattime)),
    );
    foreach ($info as $k => $v) {
        if ($v == "-") {
            $out .= "<br />";
            continue;
        }
        $out .= " &bull; <font color='#ffff00'>$k:</font> $v<br />";
    }

	# set some flags
	$is_corp_mgr = ($run->corpid == get_corp_id($ft->eve->CorpName)) ? 1 : 0;
	$is_site_mgr = $obj->manager();
	$is_mgr = $is_corp_mgr || $is_site_mgr;

    # now give them some commands
    if ($is_mgr) {
		$out .= "<br />";
        if (is_null($run->ranattime)) {
			# run options
			if ($is_corp_mgr) {
            	$out .= "[ <a href='$_WEB_URL/igb-runcomplete.php?id=$id&schedid=$schedid'>Run Completed</a> ] ";
            	$out .= "[ <a href='$_WEB_URL/igb-abandonrun.php?id=$id&schedid=$schedid'>Abandon This Run</a> ] ";
			} elseif ($is_site_mgr) {
            	$out .= "[ <a href='$_WEB_URL/igb-runcomplete.php?id=$id&schedid=$schedid&admin=1'>Admin: Run Completed</a> ] ";
				$out .= "[ <a href='$_WEB_URL/igb-abandonrun.php?id=$id&schedid=$schedid&admin=1'>Admin: Abandon This Run</a> ]";
			}
        }
		$out .= "<br /><br />";
    }

	# now comment crap
	include('igb-showcomments.php');

    # all done
    echo("<html><title>Plexer - The Complex Scheduler</title><body>$out</body></html>");

?>

