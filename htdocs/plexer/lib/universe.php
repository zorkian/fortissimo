<?php

    # load complexes
    function load_complex_list() {
        global $ft;
        $rows = $ft->dbh->_select_rows_as_objects('SELECT * FROM tbl:plexes ORDER BY region, name');
        if (! $rows) {
            return array();
        }
        return $rows;
    }

    # get a complex
    function load_complex($id) {
        global $ft;
        $row = $ft->dbh->_select_row_as_object('SELECT * FROM tbl:plexes WHERE plexid = ?',
                                               array($id));
        return $row;
    }

	# get the count of comments on a schedule
	function get_comment_count($schedid) {
		global $ft;
		$ct = $ft->dbh->_select_one('SELECT COUNT(*) FROM tbl:comments WHERE schedid = ?',
									array($schedid));
		if (! $ct) {
			return 0;
		}
		return $ct;
	}
	
	function get_comments($schedid) {
		global $ft;
		$rows = $ft->dbh->_select_rows_as_objects('SELECT *, FROM_UNIXTIME(lefttime) AS "leftdate" FROM tbl:comments WHERE schedid = ?',
		                                          array($schedid));
		if (! $rows) {
			return array();
		}
		return $rows;
	}
	
	function add_comment($id, $schedid, $pilotid, $comment) {
		global $ft;
		$ft->dbh->_do_query('INSERT INTO tbl:comments (commentid, plexid, schedid, pilotid, lefttime, content) ' .
		                    'VALUES (NULL, ?, ?, ?, UNIX_TIMESTAMP(), ?)', array($id, $schedid, $pilotid, $comment));
	}

    # get upcoming slots
    function get_upcoming_slots($id, $days_forward = 14, $days_backward = 3) {
        global $ft;

        # now go dig up slots!!!!!
        $slots = $ft->dbh->_select_rows_as_objects('SELECT slotid, plexid, name, longdesc ' .
                                                   'FROM tbl:slots WHERE plexid = ? AND active = 1 ORDER BY name',
                                                   array($id));
        if (! $slots) {
            return array();
        }

        # now we want to iterate over the number of days requested
        $now = time();
        $res = array();
        for ($i = -$days_backward; $i < $days_forward; $i++) {
            foreach ($slots as $slot) {
                array_push($res, array(date("Ymd", time() + 86400*$i), $slot, ($i < 0 ? 1 : 0)));
            }
        }
        return $res;
    }

    function slot_to_date($slot) {
        $ct = sscanf($slot, "%4d%2d%2d", $year, $mon, $day);
        if ($ct == 3) {
            return mktime(0, 0, 0, $mon, $day, $year);
        } else {
            return null;
        }
    }

    function load_run_schedid($id, $schedid) {
        global $ft;
        $row = $ft->dbh->_select_row_as_object('SELECT * FROM tbl:schedule WHERE plexid = ? AND schedid = ?',
                                               array($id, $schedid));
        return $row;
    }

    function load_run($id, $dayid, $slotid) {
        global $ft;
        $row = $ft->dbh->_select_row_as_object('SELECT * FROM tbl:schedule WHERE plexid = ? AND dayid = ? AND slotid = ?',
                                               array($id, $dayid, $slotid));
        return $row;
    }

    function stddate($time) {
        return date("Y-m-d", $time);
    }

    # get scheduled items
    function get_schedules($plexid, $slots) {
        # get slots
        global $ft;

        # create array of days
        $days = array();
        foreach ($slots as $s) {
            array_push($days, $s[0]);
        }

        # now select them
        $rows = $ft->dbh->_select_rows_as_objects('SELECT * FROM plexer_schedule WHERE plexid = ? AND dayid IN (?)',
                                                  array($plexid, $days));
        if (! $rows) {
            return array();
        }
        $out = array();
        foreach ($rows as $row) {
            if (! is_array($out[$row->dayid])) {
                $out[$row->dayid] = array();
            }
            $out[$row->dayid][$row->slotid] = $row;
        }
        return $out;
    }

    function abandon_run($plexid, $schedid) {
        global $ft;
        $ft->dbh->_do_query('DELETE FROM tbl:schedule WHERE plexid = ? AND schedid = ?',
                            array($plexid, $schedid));
        return 1;
    }

    function mark_run_complete($plexid, $schedid) {
        global $ft;
        $ft->dbh->_do_query('UPDATE tbl:schedule SET ranattime = UNIX_TIMESTAMP() WHERE plexid = ? AND schedid = ?',
                            array($plexid, $schedid));
        return 1;
    }

    function submit_schedule($plexid, $dayid, $slotid, $corpid, $pilotid) {
        global $ft;
        $ft->dbh->_do_query('INSERT INTO tbl:schedule (schedid, plexid, dayid, slotid, corpid, pilotid, ranattime) '.
                            'VALUES (NULL, ?, ?, ?, ?, ?, NULL)', array($plexid, $dayid, $slotid, $corpid, $pilotid));
        $run = load_run($plexid, $dayid, $slotid);
        return $run;
    }

    function get_complex_slot_name($id) {
        global $ft;
        $sys = $ft->dbh->_select_row_as_object('SELECT name FROM tbl:slots WHERE slotid = ?',
                                               array($id));
        if ($sys) {
            return $sys->name;
        }

        return null;
    }

    function get_complex_slot_desc($id) {
        global $ft;
        $sys = $ft->dbh->_select_row_as_object('SELECT longdesc FROM tbl:slots WHERE slotid = ?',
                                               array($id));
        if ($sys) {
            return $sys->longdesc;
        }

        return null;
    }

    function get_complex_name($id) {
        global $ft;
        $sys = $ft->dbh->_select_row_as_object('SELECT name FROM tbl:plexes WHERE plexid = ?',
                                               array($id));
        if ($sys) {
            return $sys->name;
        }

        return null;
    }

    # given system name, return systemid
    function get_system_id($name, $security = null) {
        global $ft;
        $sys = $ft->dbh->_select_row_as_object('SELECT systemid FROM tbl:systems WHERE name = ?',
                                               array($name));
        if ($sys) {
            return $sys->systemid;
        }

        if ($security) {
            $ft->dbh->_do_query('INSERT INTO tbl:systems (name, regionid, security) VALUES (?, ?, ?)',
                                array($name, null, $security));
            return get_system_id($name);
        }

        return 0;
    }

    function _cache($area, $key, $set = null) {
        global $_CACHE;
        if (! is_null($set)) {
            if (! isset($_CACHE[$area])) {
                $_CACHE[$area] = array();
            }
#            echo("[[ SET $area -> $key = $set ]]<br />\n");
            $_CACHE[$area][$key] = $set;
        } else {
            if (isset($_CACHE[$area])) {
                if (isset($_CACHE[$area][$key])) {
#                    echo("[[ RETURN $area -> $key ]]<br />\n");
                    return $_CACHE[$area][$key];
                } else {
                    return null;
                }
            } else {
                $_CACHE[$area] = array();
                return null;
            }
        }
    }
    
    # given systemid, return system name
    function get_pilot_charid($pilotid) {
        if ($name = _cache("pilot_charid", $pilotid)) {
            return $name;
        }
        global $ft;
        $sys = $ft->dbh->_select_row_as_object('SELECT charid FROM tbl:pilots WHERE pilotid = ?',
                                               array($pilotid));
        if ($sys) {
            _cache("pilot_charid", $pilotid, $sys->charid);
            return $sys->charid;
        }
        return null;
    }

    # given systemid, return system name
    function get_pilot_corpid($pilotid) {
        if ($name = _cache("pilot_corpid", $pilotid)) {
            return $name;
        }
        global $ft;
        $sys = $ft->dbh->_select_row_as_object('SELECT corpid FROM tbl:pilots WHERE pilotid = ?',
                                               array($pilotid));
        if ($sys) {
            _cache("pilot_corpid", $pilotid, $sys->corpid);
            return $sys->corpid;
        }
        return null;
    }

    # given systemid, return system name
    function get_pilot_name($pilotid) {
        if ($name = _cache("pilot_name", $pilotid)) {
            return $name;
        }
        global $ft;
        $sys = $ft->dbh->_select_row_as_object('SELECT name FROM tbl:pilots WHERE pilotid = ?',
                                               array($pilotid));
        if ($sys) {
            _cache("pilot_name", $pilotid, $sys->name);
            return $sys->name;
        }
        return null;
    }

    # given systemid, return system security
    function get_system_security($systemid) {
        if ($name = _cache("system_security", $systemid)) {
            return $name;
        }
        global $ft;
        $sys = $ft->dbh->_select_row_as_object('SELECT security FROM tbl:systems WHERE systemid = ?',
                                               array($systemid));
        if ($sys) {
#            $sec = sprintf("%0.1f", $sys->security / 100);
            _cache("system_security", $systemid, $sys->security);
            return $sys->security;
        }
        return null;
    }

    # given systemid, return system name
    function get_system_name($systemid) {
        if ($name = _cache("system_name", $systemid)) {
            return $name;
        }
        global $ft;
        $sys = $ft->dbh->_select_row_as_object('SELECT name FROM tbl:systems WHERE systemid = ?',
                                               array($systemid));
        if ($sys) {
            _cache("system_name", $systemid, $sys->name);
            return $sys->name;
        }
        return null;
    }

    # given systemid, return system name
    function get_system_constellation_id($systemid) {
        if ($name = _cache("system_constellation_id", $systemid)) {
            return $name;
        }
        global $ft;
        $sys = $ft->dbh->_select_row_as_object('SELECT constellationid FROM tbl:systems WHERE systemid = ?',
                                               array($systemid));
        if ($sys) {
            _cache("system_constellation_id", $systemid, $sys->constellationid);
            return $sys->constellationid;
        }
        return null;
    }

    # given systemid, return system name
    function get_system_region_id($systemid) {
        if ($name = _cache("system_region_id", $systemid)) {
            return $name;
        }
        global $ft;
        $sys = $ft->dbh->_select_row_as_object('SELECT regionid FROM tbl:systems WHERE systemid = ?',
                                               array($systemid));
        if ($sys) {
            _cache("system_region_id", $systemid, $sys->regionid);
            return $sys->regionid;
        }
        return null;
    }

    # given constellation name, return constellationid
    function get_constellation_id($name, $first = 1) {
        global $ft;
        $sys = $ft->dbh->_select_row_as_object('SELECT constellationid FROM tbl:constellations WHERE name = ?',
                                               array($name));
        if ($sys) {
            return $sys->constellationid;
        }

        if ($first) {
            $ft->dbh->_do_query('INSERT INTO tbl:constellations (name) VALUES (?)',
                                array($name));
            return get_constellation_id($name, 0); # 0 to not retry the insert if we fail again
        }

        return 0;
    }

    # given region name, return regionid
    function get_region_id($name, $first = 1) {
        global $ft;
        $sys = $ft->dbh->_select_row_as_object('SELECT regionid FROM tbl:regions WHERE name = ?',
                                               array($name));
        if ($sys) {
            return $sys->regionid;
        }

        if ($first) {
            $ft->dbh->_do_query('INSERT INTO tbl:regions (name) VALUES (?)',
                                array($name));
            return get_region_id($name, 0); # 0 to not retry the insert if we fail again
        }

        return 0;
    }

    # given systemid, return system name
    function get_item_group_name($regionid) {
        return get_group_name(get_item_group_id($regionid));
    }

    # given systemid, return system name
    function get_item_group_id($regionid) {
        if ($name = _cache("item_group_id", $regionid)) {
            return $name;
        }
        global $ft;
        $sys = $ft->dbh->_select_row_as_object('SELECT groupid FROM tbl:itemtypes WHERE typeid = ?',
                                               array($regionid));
        if ($sys) {
            _cache("item_group_id", $regionid, $sys->groupid);
            return $sys->groupid;
        }
        return null;
    }

    # given systemid, return system name
    function get_item_killpoints($regionid) {
        if ($name = _cache("item_killpoints", $regionid)) {
            return $name;
        }
        global $ft;
        $sys = $ft->dbh->_select_row_as_object('SELECT killpoints FROM tbl:itemtypes WHERE typeid = ?',
                                               array($regionid));
        if ($sys) {
            _cache("item_killpoints", $regionid, $sys->killpoints);
            return $sys->killpoints;
        }
        return null;
    }

    # given systemid, return system name
    function get_item_bountypoints($regionid) {
        if ($name = _cache("item_bountypoints", $regionid)) {
            return $name;
        }
        global $ft;
        $sys = $ft->dbh->_select_row_as_object('SELECT bountypoints FROM tbl:itemtypes WHERE typeid = ?',
                                               array($regionid));
        if ($sys) {
            _cache("item_bountypoints", $regionid, $sys->bountypoints);
            return $sys->bountypoints;
        }
        return null;
    }

    # given systemid, return system name
    function get_group_name($regionid) {
        if ($name = _cache("group_name", $regionid)) {
            return $name;
        }
        global $ft;
        $sys = $ft->dbh->_select_row_as_object('SELECT name FROM tbl:itemgroups WHERE groupid = ?',
                                               array($regionid));
        if ($sys) {
            _cache("group_name", $regionid, $sys->name);
            return $sys->name;
        }
        return null;
    }

    # given systemid, return system name
    function get_item_icon($regionid) {
        if ($name = _cache("item_icon", $regionid)) {
            return $name;
        }
        global $ft;
        $sys = $ft->dbh->_select_row_as_object('SELECT icon FROM tbl:itemtypes WHERE typeid = ?',
                                               array($regionid));
        if ($sys) {
            _cache("item_icon", $regionid, $sys->icon);
            return $sys->icon;
        }
        return null;
    }

    # given systemid, return system name
    function get_item_name($regionid) {
        if ($name = _cache("item_name", $regionid)) {
            return $name;
        }
        global $ft;
        $sys = $ft->dbh->_select_row_as_object('SELECT name, seen FROM tbl:itemtypes WHERE typeid = ?',
                                               array($regionid));
        if ($sys) {
            _cache("item_name", $regionid, $sys->name);
            if (! $sys->seen) {
                # now that we've seen this item, or someone's asked about it, mark it as seen
                # so we can add it to the right forms
                $ft->dbh->_do_query('UPDATE tbl:itemtypes SET seen = 1 WHERE typeid = ?', array($regionid));
            }
            return $sys->name;
        }
        return null;
    }

    # given systemid, return system name
    function get_corp_allowed($regionid) {
        if ($name = _cache("corp_allowed", $regionid)) {
            return $name;
        }
        global $ft;
        $sys = $ft->dbh->_select_row_as_object('SELECT allowed FROM tbl:corps WHERE corpid = ?',
                                               array($regionid));
        if ($sys) {
            _cache("corp_allowed", $regionid, $sys->allowed);
            return $sys->allowed;
        }
        return null;
    }

    # given systemid, return system name
    function get_corp_ticker($regionid, $null_if_empty = 0) {
        if (! is_null($name = _cache("corp_ticker", $regionid))) {
            return $name;
        }
        global $ft;
        $sys = $ft->dbh->_select_row_as_object('SELECT ticker FROM tbl:corps WHERE corpid = ?',
                                               array($regionid));
        if ($sys) {
            $ticker = $sys->ticker;
            if (is_null($ticker) && ! $null_if_empty) {
                $ticker = '????';
            }
            _cache("corp_ticker", $regionid, $ticker);
            return $ticker;
        }
        return null;
    }

    # given systemid, return system name
    function get_corp_name($regionid) {
        if (is_null($regionid)) {
            return null;
        }
        if ($name = _cache("corp_name", $regionid)) {
            return $name;
        }
        global $ft;
        $sys = $ft->dbh->_select_row_as_object('SELECT name FROM tbl:corps WHERE corpid = ?',
                                               array($regionid));
        if ($sys) {
            _cache("corp_name", $regionid, $sys->name);
            return $sys->name;
        }
        return null;
    }

    # given systemid, return system name
    function get_corp_warmode($regionid) {
        if (! is_null($name = _cache("corp_warmode", $regionid))) {
            return $name;
        }
        global $ft;
        $sys = $ft->dbh->_select_row_as_object('SELECT warmode FROM tbl:corps WHERE corpid = ?',
                                               array($regionid));
        if ($sys) {
            _cache("corp_warmode", $regionid, $sys->warmode);
            return $sys->warmode;
        }
        return null;
    }

    # given systemid, return system name
    function get_corp_allianceid($regionid) {
        if ($name = _cache("corp_allianceid", $regionid)) {
            return $name;
        }
        global $ft;
        $sys = $ft->dbh->_select_row_as_object('SELECT allianceid FROM tbl:corps WHERE corpid = ?',
                                               array($regionid));
        if ($sys) {
            _cache("corp_allianceid", $regionid, $sys->allianceid);
            return $sys->allianceid;
        }
        return null;
    }

    function get_pilot_link($pilotid) {
        global $_WEB_URL;
        $url = $_WEB_URL . "/show_pilot.php?pilotid=" . $pilotid;
        return "<a href='$url'>" . get_pilot_name($pilotid) . "</a>";
    }
    function get_alliance_link($allianceid) {
        global $_WEB_URL;
        $url = $_WEB_URL . "/show.php?allianceid=" . $allianceid;
        return "<a href='$url'>" . get_alliance_name($allianceid) . "</a>";
    }
    function get_corp_link($corpid) {
        global $_WEB_URL;
        $url = $_WEB_URL . "/show.php?corpid=" . $corpid;
        return "<a href='$url'>" . get_corp_name($corpid) . "</a>";
    }
    function get_weapon_link($weaponid) {
        global $_WEB_URL;
        $url = $_WEB_URL . "/show.php?weaponid=" . $weaponid;
        return "<a href='$url'>" . get_weapon_name($weaponid) . "</a>";
    }
    function get_group_link($groupid) {
        global $_WEB_URL;
        $url = $_WEB_URL . "/show.php?groupid=" . $groupid;
        return "<a href='$url'>" . get_group_name($groupid) . "</a>";
    }
    function get_ship_link($shipid) {
        global $_WEB_URL;
        $url = $_WEB_URL . "/show.php?shipid=" . $shipid;
        return "<a href='$url'>" . get_item_name($shipid) . "</a>";
    }
    function get_item_link($itemid) {
        global $_WEB_URL;
        $url = $_WEB_URL . "/show.php?itemid=" . $itemid;
        return "<a href='$url'>" . get_item_name($itemid) . "</a>";
    }
    function get_constellation_link($constellationid) {
        global $_WEB_URL;
        $url = $_WEB_URL . "/show.php?constellationid=" . $constellationid;
        return "<a href='$url'>" . get_constellation_name($constellationid) . "</a>";
    }
    function get_region_link($regionid) {
        global $_WEB_URL;
        $url = $_WEB_URL . "/show.php?regionid=" . $regionid;
        return "<a href='$url'>" . get_region_name($regionid) . "</a>";
    }
    function get_system_link($systemid) {
        global $_WEB_URL;
        $url = $_WEB_URL . "/show.php?systemid=" . $systemid;
        return "<a href='$url'>" . get_system_name($systemid) . "</a>";
    }

    # given systemid, return system name
    function get_alliance_name($regionid) {
        if ($regionid <= 0) {
            return null;
        }
        if ($name = _cache("alliance_name", $regionid)) {
            return $name;
        }
        global $ft;
        $sys = $ft->dbh->_select_row_as_object('SELECT name FROM tbl:alliances WHERE allianceid = ?',
                                               array($regionid));
        if ($sys) {
            _cache("alliance_name", $regionid, $sys->name);
            return $sys->name;
        }
        return null;
    }

    # given systemid, return system name
    function get_constellation_name($constellationid) {
        if ($name = _cache("constellation_name", $constellationid)) {
            return $name;
        }
        global $ft;
        $sys = $ft->dbh->_select_row_as_object('SELECT name FROM tbl:constellations WHERE constellationid = ?',
                                               array($constellationid));
        if ($sys) {
            _cache("constellation_name", $constellationid, $sys->name);
            return $sys->name;
        }
        return null;
    }

    # given systemid, return system name
    function get_region_name($regionid) {
        if ($name = _cache("region_name", $regionid)) {
            return $name;
        }
        global $ft;
        $sys = $ft->dbh->_select_row_as_object('SELECT name FROM tbl:regions WHERE regionid = ?',
                                               array($regionid));
        if ($sys) {
            _cache("region_name", $regionid, $sys->name);
            return $sys->name;
        }
        return null;
    }

    function get_pilot_with_info($charid, $name, $corpname, $alliancename) {
        # creates the pilot if needed
        $allid = get_alliance_id($alliancename);
        $corpid = get_corp_id($corpname, $allid);
        $pilotid = get_pilot_id($name, $corpid);
        $ocid = get_pilot_charid($pilotid);
        if ($ocid != $charid) {
            global $ft;
            $ft->dbh->_do_query('UPDATE tbl:pilots SET charid = ? WHERE pilotid = ?',
                                array($charid, $pilotid));
        }
        return $pilotid;
    }

    function get_corp_id_fuzzy($name) {
        global $ft;
        if (is_null($name)) {
            return 0;
        }
        $corps = $ft->dbh->_select_rows_as_objects('SELECT corpid FROM tbl:corps WHERE name LIKE ? ORDER BY name', array($name . '%'));
        $out = array();
        if ($corps) {
            foreach ($corps as $c) {
                array_push($out, $c->corpid);
            }
        }
        if (count($out) == 0) {
            return null;
        } elseif (count($out) > 1) {
            return $out;
        } else {
            return $out[0];
        }
    }
    
    function get_pilot_id_fuzzy($name) {
        global $ft;
        if (is_null($name)) {
            return 0;
        }
        $pilots = $ft->dbh->_select_rows_as_objects('SELECT pilotid FROM tbl:pilots WHERE name LIKE ? ORDER BY name', array($name . '%'));
        $out = array();
        if ($pilots) {
            foreach ($pilots as $c) {
                array_push($out, $c->pilotid);
            }
        }
        if (count($out) == 0) {
            return null;
        } elseif (count($out) > 1) {
            return $out;
        } else {
            return $out[0];
        }
    }
    
    function get_pilot_id($name, $corpid = null, $first = 1) {
        global $ft;
        if (is_null($name)) {
            return 0;
        }
        $pilot = $ft->dbh->_select_row_as_object('SELECT pilotid FROM tbl:pilots WHERE name = ?', array($name));
        $pilotid = null;
        if ($pilot) {
            $pilotid = $pilot->pilotid;
            if ($corpid && $pilot->corpid != $corpid) {
                $ft->dbh->_do_query('UPDATE tbl:pilots SET corpid = ? WHERE pilotid = ?', array($corpid, $pilotid));
            }
        }
        if (! $pilotid && $name != "" && $first) {
            $ft->dbh->_do_query('INSERT INTO tbl:pilots (name, corpid) VALUES (?, ?)', array($name, $corpid));
            $pilotid = get_pilot_id($name, $corpid, 0);
        }
        return $pilotid;
    }
    
    function get_corp_id($name, $allianceid = null, $first = 1) {
        global $ft;
        if (is_null($name)) {
            return 0;
        }
        $corp = $ft->dbh->_select_row_as_object('SELECT corpid, allianceid FROM tbl:corps WHERE name = ?', array($name));
        $corpid = null;
        if ($corp) {
            $corpid = $corp->corpid;
            if ($allianceid && $corp->allianceid != $allianceid) {
                $ft->dbh->_do_query('UPDATE tbl:corps SET allianceid = ? WHERE corpid = ?', array($allianceid, $corpid));
            }
        }
        if (! $corpid && ! is_null($allianceid) && $first) {
            if ($allianceid <= 0) {
                $allianceid = null;
            }
            $ft->dbh->_do_query('INSERT INTO tbl:corps (name, allianceid) VALUES (?, ?)', array($name, $allianceid));
            $corpid = get_corp_id($name, $allianceid, 0);
        }
        return $corpid;
    }

    function get_alliance_id($name, $first = 1) {
        global $ft;
        if (is_null($name) || $name == 'None' || $name == 'Unknown') {
            # None/Unknown seems to be a hint used by the game when a corp doesn't have an alliance
            return 0;
        }
        $allianceid = $ft->dbh->_select_one('SELECT allianceid FROM tbl:alliances WHERE name = ?', array($name));
        if (! $allianceid && $first) {
            $ft->dbh->_do_query('INSERT INTO tbl:alliances (name) VALUES (?)', array($name));
            $allianceid = get_alliance_id($name, 0);
        }
        return $allianceid;
    }

    function get_item_id($name) {
        if ($id = _cache("item_id", $name)) {
            return $id;
        }
        global $ft;
        if (is_null($name)) {
            return 0;
        }
        $shipid = $ft->dbh->_select_one('SELECT typeid FROM tbl:itemtypes WHERE name = ?', array($name));
        _cache("item_id", $name, $shipid);
        return $shipid;
    }

    function load_kills_by_id($ids) {
        global $ft;
        $out = array();
        if (count($ids) <= 0) {
            return $out;
        }

        # try from cache first
        $cached = $ft->dbh->_select_rows_as_objects('SELECT * FROM tbl:killcache WHERE killid IN (?)',
                                                    array($ids));
        foreach ($cached as $c) {
            $out[$c->killid] = unserialize($c->cache);
        }

        # now remove the ones we got from the load list
        $load = array();
        foreach ($ids as $id) {
            if (! isset($out[$id])) {
                array_push($load, $id);
            }
        }

        # if we're done, we're done
        if (count($load) <= 0) {
            return $out;
        }

        # guess not, try to load from database
        $data = $ft->dbh->_select_rows_as_objects('SELECT *, FROM_UNIXTIME(killtime) AS "date" FROM tbl:summary WHERE killid IN (?)',
                                                  array($load));
        foreach ($data as $r) {
            $k = new KillMail;
            $k->kill_id = $r->killid;
            $k->system_id = $r->systemid;
            $k->region_id = get_system_region_id($k->system_id);
            $k->constellation_id = get_system_constellation_id($k->system_id);
            $k->killtime = $r->killtime;
            $k->raw_id = $r->mailid;

            # strip the time...
            $date = $r->date;
            $k->date = preg_replace("/\s\d\d:\d\d:\d\d$/", "", $date);

            # now get the victim
            $k->victim = new Party;
            $k->victim->pilot_id = $r->v_pilotid;
            $k->victim->corp_id = $r->v_corpid;
            $k->victim->alliance_id = $r->v_allianceid;
            $k->victim->ship_id = $r->v_shipid;
            $k->victim->group_id = $r->v_groupid;

            # and now the final killer
            $final = new Party();
            $final->pilot_id = $r->k_pilotid;
            $final->corp_id = $r->k_corpid;
            $final->alliance_id = $r->k_allianceid;
            $final->ship_id = $r->k_shipid;
            $final->group_id = $r->k_groupid;
            $final->security = $r->k_security;
            $final->weapon_id = $r->weaponid;
            $final->finalblow = true;
            $k->attackers = array( $final );
            $k->killer = $final;

            # get other killers ...
            $killers = $ft->dbh->_select_rows_as_objects('SELECT * FROM tbl:killers WHERE killid = ? AND finalblow = 0',
                                                         array($k->kill_id));
            foreach ($killers as $killer) {
                $p = new Party();
                $p->pilot_id = $killer->pilotid;
                $p->corp_id = $killer->corpid;
                $p->alliance_id = $killer->allianceid;
                $p->ship_id = $killer->shipid;
                $p->group_id = $killer->groupid;
                $p->security = $killer->security;
                $p->weapon_id = $killer->weaponid;
                $p->finalblow = false;
                array_push($k->attackers, $p);
            }

            # and now get the items destroyed
            $items = $ft->dbh->_select_rows_as_objects('SELECT * FROM tbl:killitems WHERE killid = ?',
                                                       array($k->kill_id));
            foreach ($items as $item) {
                $p = new Item();
                $p->item_id = $item->itemid;
                $p->quantity = $item->quantity;
                $p->loc = $item->loc;
                array_push($k->destroyed, $p);
            }

            # done
            $k->reverse_ids();
            $out[$k->kill_id] = $k;

            # cache it
            $ft->dbh->_do_query('INSERT INTO tbl:killcache (killid, cache) VALUES (?, ?)',
                                array($k->kill_id, serialize($k)));
        }

        return $out;
    }

    # WARNING - THIS RESETS ALL DATA IN ALL TABLES - probably not a good idea to do this
    # very often, but CAN be useful if the parsing algorithms have changed, or the stats
    # have messed up due to some unforseen bug
    function reset_data() {
        global $ft;
        $tables = array('dupeids', 'summary', 'stats', 'killers', 'killitems', 'favorites', 'killcache');
        foreach ($tables as $t) {
            $ft->dbh->_do_query("DELETE FROM tbl:$t");
        }
    }

    function reprocess_all_mail($start = 0) {
        if ($start == 0) {
            reset_data();
        }
        $output = array();

        global $ft;
        $ids = $ft->dbh->_select_column('SELECT mailid FROM tbl:rawmail WHERE mailid >= ? ORDER BY mailid LIMIT 10', array($start));
        foreach ($ids as $id) {
            $mail = $ft->dbh->_select_one('SELECT mail FROM tbl:rawmail WHERE mailid = ?', array($id));
            if ($mail) {
                $obj = new KillMail($mail, $id);
                if ($obj->parsed) {
                    array_push($output, array('ok', "<a href='/killmail.php?killid=$id&raw=1'>Mail #$id</a> " .
                                                    "processed: " . $obj->victim->pilot . " lost a " .
                                                    $obj->victim->ship . " in " . $obj->system . ".", $id));
                    $obj->adjust_totals();
                } else {
                    array_push($output, array('err', "<a href='/killmail.php?killid=$id&raw=1'>Mail #$id</a> failed to process.", $id));
                }
            }
        }
        
        return $output;
    }

    function get_sorted_links() {
        global $ft;
        $links = $ft->dbh->_select_rows_as_objects('SELECT * FROM tbl:sitelinks ORDER BY sort');
        return $links;
    }

    function get_top10($which, $whichwhat, $what) {
        global $ft;
        $t10 = $ft->dbh->_select_rows_as_objects('SELECT * FROM tbl:favorites WHERE type = ? ' .
                                                 'AND var1 = ? AND ftype = ? ORDER BY var3 DESC LIMIT 10',
                                                 array($which, $whichwhat, $what));
        $out = array();
        foreach ($t10 as $row) {
            $val = "value undefined";
            if ($what == 'weapon') {
                $val = get_item_name($row->var2);
            } elseif ($what == 'pilot') {
                $val = get_pilot_name($row->var2) . ' [' . get_corp_ticker(get_pilot_corpid($row->var2)) . ']';
            } elseif ($what == 'system') {
                $val = get_system_name($row->var2);
            } elseif ($what == 'ship_killed' || $what == 'ship_lost' || $what == 'ship_flown') {
                $val = get_item_name($row->var2);
            }
            array_push($out, array($val, $row->var3));
        }
        return $out;
    }

    # classes we use
    class FtSystem {
        var $systemid = null;
        var $regionid = null;
        var $name = null;
        var $security = null;
        
        function USystem($nameorid, $security = null) {
            if (is_null($nameorid)) {
                return;
            }

            $systemid = null;
            if (! is_numeric($nameorid)) {
                $systemid = get_system_id($nameorid, $security);
            } else {
                $systemid = $nameorid;
            }

            if (is_null($systemid)) {
                return;
            }

            global $ft;
            $sys = $ft->dbh->_select_row_as_object('SELECT * FROM tbl:systems WHERE systemid = ?',
                                                   array($systemid));
            if (! $sys) {
                return;
            }

            $this->systemid = $sys->systemid;
            $this->regionid = $sys->regionid;
            $this->name = $sys->name;
            $this->security = $sys->security;
        }
    }

?>
