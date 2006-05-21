<?php

    # general purpose cacheing function that, given an area and a key, will see if
    # we have a value stored.  optional third argument is to set the value for that
    # cached item.
    #
    # $var = _cache("pilot_obj", $pilotid)
    #    returns whatever pilot_obj we have with the $pilotid as the key, null on none
    #
    # _cache("pilot_obj", 1, $obj)
    #    set pilot_obj with id of 1 to be $obj
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
    function get_corp_link($corpid) {
        global $_WEB_URL;
        $url = $_WEB_URL . "/show_corp.php?corpid=" . $corpid;
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

            $sys = new EVESystem( $r->systemid );
            $k->system_id = $sys->getId();
            $k->region_id = $sys->getRegionId();
            $k->constellation_id = $sys->getConstellationId();
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
                $val = EVESystem::getName( $row->var2 );
            } elseif ($what == 'ship_killed' || $what == 'ship_lost' || $what == 'ship_flown') {
                $val = get_item_name($row->var2);
            }
            array_push($out, array($val, $row->var3));
        }
        return $out;
    }

?>
