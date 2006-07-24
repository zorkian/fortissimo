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

    # store output here
    $out = "<center><b><font color='#ffff00'>Plexer - The Complex Scheduler</font></b><br />";
    $out .= "<font color='#ff0000'><b>$plex->name</b> in <b>$plex->system</b> rated <b>$plex->rating/10</b></font><br />";
    $out .= "[ <a href='$_WEB_URL/'>Back to Top</a> ]";
    $out .= "</center><br />";

    # dump information about this plex
    $slots = get_upcoming_slots($id, 14, 3); # 14 days forward, 3 days backwards
    if ($slots && count($slots)) {
        $scheds = get_schedules($id, $slots); # get schedules for these slots
        $out .= "<ul>";
        foreach ($slots as $d) {
            $dayid = $d[0];
            $slot = $d[1];
			$inpast = $d[2];
            $slotid = $slot->slotid;

            $out .= "<li>";
            $out .= stddate(slot_to_date($dayid));
            $out .= " - " . $slot->name;
            if ($scheds[$dayid][$slotid]) {
                $schedid = $scheds[$dayid][$slotid]->schedid;

                # setup tags we want
                $tag = "";
                if (! is_null($scheds[$dayid][$slotid]->ranattime)) {
                    $tag = "<font color='#00ff00'>(DONE)</font> ";
                }

                # now let's print out basic information
                $out .= " - $tag<font color='#ff0000'>";
                $out .= "<b>" . get_corp_name($scheds[$dayid][$slotid]->corpid) . "</b></font>";

				# FIXME: access restrictions here please
                if (1) {
                    $out .= " [ <b><a href='$_WEB_URL/igb-scheduleinfo.php?id=$id&schedid=$schedid'>See Run Information</a></b> ]";
                }

				# show comments
				$ccount = get_comment_count($schedid);
				if ($ccount > 0) {
					$out .= " <font color='green'><b>($ccount comments)</b></font>";
				}
            } else {
                $out .= " - <font color='#ffff00'>unreserved</font>";

				# FIXME: access restrictions here please
				if ($inpast) {
					# meh, say something?
				} else {
                	if (1) {
                    	$out .= " [ <b><a href='$_WEB_URL/igb-schedulecomplex.php?id=$id&dayid=$dayid&slotid=$slotid'>Reserve</a></b> ]";
                	}
				}
            }
            $out .= "</li>";
        }
        $out .= "</ul>";
    } else {
        $out .= "<p>Sorry, there don't seem to be any defined run slots for this complex.</p>";
    }

    # all done
    echo("<html><title>Plexer - The Complex Scheduler</title><body>$out</body></html>");

?>

