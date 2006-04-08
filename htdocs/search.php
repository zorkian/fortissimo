<?php

    include('lib/fortissimo.php');

    $ft->title('Kill Search');

    # bail out early if not searching yet
    if (! $_GET['go']) {
        $ft->makepage('search');
        return;
    }

    # get the input
    $whatis = $_GET['whatis'];
    $what = trim($_GET['what']);
    $which = $_GET['which'];
    if ($_GET['start_month'] > 0) {
        $startdate = sprintf("%04d-%02d-%02d", $_GET['start_year'], $_GET['start_month'], $_GET['start_day']);
    }
    if ($_GET['end_month'] > 0) {
        $enddate = sprintf("%04d-%02d-%02d", $_GET['end_year'], $_GET['end_month'], $_GET['end_day']);
    }
    $output = $_GET['output'];
    $type = $_GET['type'];

    # now assign back the variables
    $ft->assign('go', 1);
    $ft->assign('whatis', $whatis);
    $ft->assign('which', $which);
    $ft->assign('start_year', $_GET['start_year']);
    $ft->assign('start_month', $_GET['start_month']);
    $ft->assign('start_day', $_GET['start_day']);
    $ft->assign('end_year', $_GET['end_year']);
    $ft->assign('end_month', $_GET['end_month']);
    $ft->assign('end_day', $_GET['end_day']);
    $ft->assign('output', $output);
    $ft->assign('type', $type);
    $ft->assign('what', $what);

    # reconstruct the query string
    $querystring = implode('&', array(
        "go=1", "whatis=$whatis", "what=$what", "which=$which",
        "start_year=" . $_GET['start_year'],
        "start_month=" . $_GET['start_month'],
        "start_day=" . $_GET['start_day'],
        "end_year=" . $_GET['end_year'],
        "end_month=" . $_GET['end_month'],
        "end_day=" . $_GET['end_day'],
        "output=$output", "type=$type",
    ));

    # and error check them
    if ($whatis != 'pilot' && $whatis != 'corp') {
        return $ft->errorpage('Invalid input.', 'search');
    }
    if (! $what) {
        return $ft->errorpage("You must enter a $whatis name to search for.", 'search');
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
    if ($output != 'kills' && $output != 'bounty' && $output != 'bountyp') {
        return $ft->errorpage('Invalid input.', 'search');
    }
    if ($type != 'kill' && $type != 'loss' && $type != 'murder') {
        return $ft->errorpage('Invalid input.', 'search');
    }

    # some more fixing, this is so MySQL can reverse the date into the time we want
    # so we end up with an inclusive end date
    $startdate = "$startdate 00:00:00";
    $enddate = "$enddate 23:59:59";

    # setup print mode or not
    $printmode = false;
    if ($output == 'bountyp') {
        $printmode = true;
        $output = 'bounty';
    }

    # now we have to perform the query
    $id = $_GET['oid'];
    $col = null;
    if ($whatis == 'pilot') {
        if (is_null($id)) {
            $id = get_pilot_id_fuzzy($what);
            if (is_array($id)) {
                # okay, show the list
                $list = array();
                foreach ($id as $cid) {
                    array_push($list, "<a href='/search.php?$querystring&oid=$cid'>" . get_pilot_name($cid) . "</a>");
                }
                $ft->assign('list', $list);
                return $ft->errorpage('Found multiple pilots.  Please select one below.', 'search');
            }
            if ($what && ! $id) {
                return $ft->errorpage('The pilot does not seem valid.  Have they ever shown up on this killboard?', 'search');
            }
        }
        $what = get_pilot_name($id);
        $term = 'pilot <strong>' . get_pilot_name($id) . '</strong>';
        $col = 'pilotid';
    } elseif ($whatis == 'corp') {
        if (is_null($id)) {
            $id = get_corp_id_fuzzy($what);
            if (is_array($id)) {
                # okay, show the list
                $list = array();
                foreach ($id as $cid) {
                    array_push($list, "<a href='/search.php?$querystring&oid=$cid'>" . get_corp_name($cid) . "</a>");
                }
                $ft->assign('list', $list);
                return $ft->errorpage('Found multiple corporations.  Please select one below.', 'search');
            }
            if ($what && ! $id) {
                return $ft->errorpage('The corporation does not seem valid.  Have they ever shown up on this killboard?', 'search');
            }
        }
        $what = get_corp_name($id);
        $term = 'corporation <strong>' . get_corp_name($id) . '</strong>';
        $col = 'corpid';
    }
    $ft->assign('search_term', $term);
    $ft->assign('what', $what);

    # if we're doing indivuals, limit
    $limit = 300;
    if ($output == 'kills') {
        $limit = 300;
    }

    # if we're searching by something...
    $bind = array();
    $colx = "";
    if ($col && $id > 0) {
        $colx = "$col = ? AND ";
        array_push($bind, $id);
    }

    # okay, can do by id/col
    $extra = "";
    if ($which == 'finalblows') {
        $extra = " AND finalblow = 1";
    }

    # do the select
    array_push($bind, $startdate, $enddate, $type);
    $data = $ft->dbh->_select_column("SELECT DISTINCT k.killid FROM tbl:killers k, tbl:summary s " .
                                     "WHERE $colx " .
                                     "      s.killtime >= UNIX_TIMESTAMP(?) " .
                                     "  AND s.killtime <= UNIX_TIMESTAMP(?) " .
                                     "  AND s.killid = k.killid " .
                                     "  AND s.type = ? " .
                                     "  $extra " .
                                     "ORDER BY s.killtime DESC " .
                                     "LIMIT $limit",
                                     $bind);
    if (count($data) == $limit) {
        $ft->assign('error', 'The maximum of 300 kills was reached and we had to abort.  Sorry!');
    }

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
            if ($whatis == 'pilot' && $id > 0 && $killer->pilot_id != $id) {
                continue;
            } elseif ($whatis == 'corp' && $id > 0 && $killer->corp_id != $id) {
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
    uksort($names, "cisort");
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
    if ($printmode) {
        $ft->display('search_print.tpl');
    } else {
        $ft->makepage('search');
    }

?>
