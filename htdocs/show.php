<?php

    include('lib/fortissimo.php');

    # figure out what they want to filter on
    $systemid = $_GET['systemid'];
    $pilotid = $_GET['pilotid'];
    $corpid = $_GET['corpid'];
    $shipid = $_GET['shipid'];
    $regionid = $_GET['regionid'];
    $groupid = $_GET['groupid'];
    $allianceid = $_GET['allianceid'];
    $weaponid = $_GET['weaponid'];
    if (! $weaponid) {
        $weaponid = $_GET['itemid'];
    }

    # now, let's build this up
    $criteria = array();
    $bind = array();
    $args = array();
    $message = array();
    if (is_numeric($systemid) && $systemid > 0) {
        array_push($criteria, 'systemid = ?');
        array_push($bind, $systemid);
        array_push($args, "systemid=$systemid");
        array_push($message, "in <strong>" . get_system_name($systemid) . "</strong>");
    }
    if (is_numeric($weaponid) && $weaponid > 0) {
        array_push($criteria, 'weaponid = ?');
        array_push($bind, $weaponid);
        array_push($args, "weaponid=$weaponid");
        array_push($message, "with a <strong>" . get_item_name($weaponid) . "</strong>");
    }
    if (is_numeric($regionid) && $regionid > 0) {
        array_push($criteria, 'regionid = ?');
        array_push($bind, $regionid);
        array_push($args, "regionid=$regionid");
        array_push($message, "in <strong>" . get_region_name($regionid) . "</strong>");
    }
    if (is_numeric($groupid) && $groupid > 0) {
        array_push($criteria, '(v_groupid = ? OR k_groupid = ?)');
        array_push($bind, $groupid, $groupid);
        array_push($args, "groupid=$groupid");
        array_push($message, "by group <strong>" . get_group_name($groupid) . "</strong>");
    }
    if (is_numeric($corpid) && $corpid > 0) {
        array_push($criteria, '(v_corpid = ? OR k_corpid = ?)');
        array_push($bind, $corpid, $corpid);
        array_push($args, "corpid=$corpid");
        array_push($message, "by corp <strong>" . get_corp_name($corpid) . " [" . get_corp_ticker($corpid) . "]</strong>");
    }
    if (is_numeric($allianceid) && $allianceid > 0) {
        array_push($criteria, '(v_allianceid = ? OR k_allianceid = ?)');
        array_push($bind, $allianceid, $allianceid);
        array_push($args, "allianceid=$allianceid");
        array_push($message, "by alliance <strong>" . get_alliance_name($allianceid) . "</strong>");
    }
    if (is_numeric($shipid) && $shipid > 0) {
        array_push($criteria, '(v_shipid = ? OR k_shipid = ?)');
        array_push($bind, $shipid, $shipid);
        array_push($args, "shipid=$shipid");
        array_push($message, "with a <strong>" . get_item_name($shipid) . "</strong>");
    }
    if (is_numeric($pilotid) && $pilotid > 0) {
        array_push($criteria, '(v_pilotid = ? OR k_pilotid = ?)');
        array_push($bind, $pilotid, $pilotid);
        array_push($args, "pilotid=$pilotid");
        array_push($message, "involving pilot <strong>" . get_pilot_name($pilotid) . "</strong>");
    }

    # reassign the args
    $a = implode('&', $args);
    $ft->assign('args', $a);

    # and criteria messaging
    $m = implode(' and ', $message);

    # show the last 50 somethings
    $ft->message('Recent activity ' . $m . '.');
    $ft->title('Recent Activity');

    # now do the select
    $crit = implode(' AND ', $criteria);

    # now prepare the page
    $ids = $ft->dbh->_select_column("SELECT killid FROM tbl:summary WHERE $crit AND type = 'kill' ORDER BY killtime DESC LIMIT 25", $bind);
    $kills = load_kills_by_id($ids, 0, 0);
    $ft->assign('kills', $kills);
    $ft->assign('killids', $ids);

    # now prepare the page
    $ids = $ft->dbh->_select_column("SELECT killid FROM tbl:summary WHERE $crit AND type = 'loss' ORDER BY killtime DESC LIMIT 25", $bind);
    $kills = load_kills_by_id($ids, 0, 0);
    $ft->assign('lkills', $kills);
    $ft->assign('lkillids', $ids);

    # now prepare the page
    $ids = $ft->dbh->_select_column("SELECT killid FROM tbl:summary WHERE $crit AND type = 'murder' ORDER BY killtime DESC LIMIT 25", $bind);
    $kills = load_kills_by_id($ids, 0, 0);
    $ft->assign('mkills', $kills);
    $ft->assign('mkillids', $ids);

    # dump the page
    $ft->title('Celestial Horizon Killboard');
    $ft->makepage('show');

?>
