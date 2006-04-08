<?php

    include('lib/fortissimo.php');

    if (! $remote) {
        return $ft->errorpage('You must be logged in to use this page.');
    }

    if (! $remote->admin()) {
        return $ft->errorpage('You must be an administrator to use this feature.');
    }

    $ft->title('Administrative Search');

    # bail out early if not searching yet
    if (! $_GET['go']) {
        $ft->makepage('search');
        return;
    }

    # get the input
    $whatis = $_GET['whatis'];
    $what = trim($_GET['what']);
    $which = $_GET['which'];
    $startdate = trim($_GET['startdate']);
    $enddate = trim($_GET['enddate']);
    $output = $_GET['output'];

    # now assign back the variables
    $ft->assign('go', 1);
    $ft->assign('whatis', $whatis);
    $ft->assign('what', $what);
    $ft->assign('which', $which);
    $ft->assign('startdate', $startdate);
    $ft->assign('enddate', $enddate);
    $ft->assign('output', $output);

    # and error check them
    if ($whatis != 'pilot' && $whatis != 'corp') {
        return $ft->errorpage('Invalid input.', 'search');
    }
    if ($which != 'killgive' && $which != 'finalblows') {
        return $ft->errorpage('Invalid input.', 'search');
    }
    if ($startdate && ! preg_match("/^\d\d\d\d-\d\d-\d\d$/", $startdate)) {
        return $ft->errorpage('Invalid starting date.  Please check your formatting and try again.', 'search');
    } elseif (! $startdate) {
        $startdate = "2000-01-01";
    }
    if ($enddate && ! preg_match("/^\d\d\d\d-\d\d-\d\d$/", $enddate)) {
        return $ft->errorpage('Invalid ending date.  Please check your formatting and try again.', 'search');
    } elseif (! $enddate) {
        # hopefully this board is not around in 2020...hehe
        $enddate = "2020-01-01";
    }
    if ($output != 'kills' && $output != 'bounty') {
        return $ft->errorpage('Invalid input.', 'search');
    }

    # some more fixing, this is so MySQL can reverse the date into the time we want
    # so we end up with an inclusive end date
    $startdate = "$startdate 00:00:00";
    $enddate = "$enddate 23:59:59";

    # now we have to perform the query
    $id = null;
    $col = null;
    if ($whatis == 'pilot') {
        $id = get_pilot_id($what);
        if (! $id) {
            return $ft->errorpage('The pilot does not seem valid.  Have they ever shown up on this killboard?', 'search');
        }
        $col = 'pilotid';
    } elseif ($whatis == 'corp') {
        $id = get_corp_id($what);
        if (! $id) {
            return $ft->errorpage('The corporation does not seem valid.  Have they ever shown up on this killboard?', 'search');
        }
        $col = 'corpid';
    }

    # okay, can do by id/col
    $extra = "";
    if ($which == 'finalblows') {
        $extra = " AND finalblow = 1";
    }

    # do the select
    $data = $ft->dbh->_select_column("SELECT k.killid FROM tbl:killers k, tbl:summary s " .
                                     "WHERE $col = ? " .
                                     "  AND s.killtime >= UNIX_TIMESTAMP(?) " .
                                     "  AND s.killtime <= UNIX_TIMESTAMP(?) " .
                                     "  AND s.killid = k.killid " .
                                     "  AND s.type = 'kill' " .
                                     "  $extra " .
                                     "GROUP BY k.killid " .
                                     "ORDER BY s.killtime",
                                     array($id, $startdate, $enddate));

    # good, now we have the right rows in our data set, load the kills
    $kills = load_kills_by_id($data);

    # if they want a bounty list, we have to do further consolidation
    if ($output == 'kills') {
        # simple, then we're done
        $ft->assign('killids', $data);
        $ft->assign('kills', $kills);
        $ft->makepage('search');
        return;
    }

    # now we have to pull together bounty information
    $bounties = array(); # id -> bountyamt total
    $earliest = array();
    $latest = array();
    $finals = array();
    $parts = array();
    $names = array();

    # now iterate over the list and start coercing data
    foreach ($kills as $k) {
        foreach (array_keys($k->attackers) as $ke) {
            # ignore anybody not in our target list
            $killer =& $k->attackers[$ke];
            if ($whatis == 'pilot' && $killer->pilot_id != $id) {
                continue;
            } elseif ($whatis == 'corp' && $killer->corp_id != $id) {
                continue;
            }

            if ($killer->finalblow) {
                $finals[$killer->pilot_id]++;
                $bounties[$killer->pilot_id] += get_item_bountypoints($k->victim->ship_id);
            } else {
                $finals[$killer->pilot_id] += 0;
                $bounties[$killer->pilot_id] += 0;
            }
            $parts[$killer->pilot_id]++;
            if (! isset($earliest[$killer->pilot_id])) {
                $earliest[$killer->pilot_id] = $k->killtime;
            } else {
                if ($k->killtime < $earliest[$killer->pilot_id]) {
                    $earliest[$killer->pilot_id] = $k->killtime;
                }
            }
            if (! isset($latest[$killer->pilot_id])) {
                $latest[$killer->pilot_id] = $k->killtime;
            } else {
                if ($k->killtime > $latest[$killer->pilot_id]) {
                    $latest[$killer->pilot_id] = $k->killtime;
                }
            }
            $names[$killer->pilot] =& $killer;
        }
    }

    # now combine into results array, dump pilot information alphabetically ?
    ksort($names);
    $out = array();
    foreach ($names as $name => $k) {
        if ($which == 'finalblows' && $finals[$k->pilot_id] <= 0) {
            continue;
        }
        array_push($out, array($k, $bounties[$k->pilot_id], $earliest[$k->pilot_id], $latest[$k->pilot_id],
                               $finals[$k->pilot_id], $parts[$k->pilot_id]));
    }

    # now output
    $ft->assign('brows', $out);
    $ft->makepage('search');

?>
