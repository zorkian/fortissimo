<?php

    include('lib/fortissimo.php');

    # get parameters
    $which = $_GET['which'];
    $type = $_GET['type'];
    $pilotid = $_GET['pilotid'];
    if ($which != 'k' && $which != 'v') {
        return $ft->errorpage("Sorry, bad input.");
    }
    if ($type != 'kill' && $type != 'loss') {
        return $ft->errorpage("Sorry, bad input.");
    }
    if (! is_numeric($pilotid) || $pilotid <= 0) {
        return $ft->errorpage("Sorry, bad input.");
    }

    # sql to get everything
    $col = $which . '_pilotid';
    $killids = $ft->dbh->_select_column("SELECT killid FROM tbl:summary WHERE $col = ? AND type = ? ORDER BY killtime DESC",
                                        array($pilotid, $type));
    $kills = load_kills_by_id($killids);

    # now prepare the page
    $ft->assign('pilot', get_pilot_link($pilotid));
    $ft->assign('kills', $kills);
    if ($which == 'k') {
        $whicharry = array('kill' => 'Kills', 'loss' => 'Losses');
        $ft->assign('which', $whicharry[$type]);
    } else {
        $whicharry = array('kill' => 'Losses', 'loss' => 'Kills');
        $ft->assign('which', $whicharry[$type]);
    }
    $ft->assign('killids', $killids);

    # dump the page
    $ft->title('Pilot History');
    $ft->makepage('all_kills');

?>
