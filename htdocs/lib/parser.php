<?php

    class Item {
        var $item = null;
        var $item_id = null;
        var $quantity = null;
        var $loc = null;
        var $icon = null;

        function get_ids() {
            if (is_null($this->item_id)) {
                $this->item_id = get_item_id($this->item);
            }
        }

        function reverse_ids() {
            $this->item = get_item_name($this->item_id);
            $this->icon = get_item_icon($this->item_id);
            if (is_null($this->icon) || $this->icon == "") {
                # if no icon, use the ? icon
                $this->icon = "07_15";
            }
        }

        function item_link() {
            if ($this->item_id && $this->item) {
                global $_WEB_URL;
                return $this->item;
#                $url = "$_WEB_URL/show_item.php?itemid=" . $this->item_id;
#                return "<a href='$url'>" . $this->item . "</a>";
            }
        }

        function img16() {
            global $_WEB_URL;
            return "<img align='left' src='$_WEB_URL/img/black/16_16/icon" .
                   $this->icon . ".png' alt='" . $this->item . "' />";
        }

        function img32() {
            global $_WEB_URL;
            return "<img align='left' src='$_WEB_URL/img/black/32_32/icon" .
                   $this->icon . ".png' alt='" . $this->item . "' />";
        }

        function img64() {
            global $_WEB_URL;
            return "<img  align='left' src='$_WEB_URL/img/black/64_64/icon" .
                   $this->icon . ".png' alt='" . $this->item . "' />";
        }
    }

    # someone who participates in a kill, as victim or attacker (object we use)
    class Party {
        var $pilot = null;
        var $pilot_id = null;
        var $char_id = null;
        var $alliance = null;
        var $alliance_id = null;
        var $corp = null;
        var $corp_ticker = null;
        var $corp_id = null;
        var $ship = null;
        var $ship_id = null;
        var $group = null;
        var $group_id = null;
        var $weapon = null;
        var $weapon_id = null;
        var $security = null;
        var $finalblow = false;

        function Party($pilotid = null) {
            if (is_null($pilotid)) {
                return;
            }

            $this->pilot_id = $pilotid;
            $this->char_id = get_pilot_charid($pilotid);
            $this->corp_id = get_pilot_corpid($pilotid);
            $this->alliance_id = get_corp_allianceid($this->corp_id);
            $this->reverse_ids();
        }

        function get_ids() {
            # order matters!!!!
            if (is_null($this->alliance_id)) {
                $this->alliance_id = get_alliance_id($this->alliance);
            }
            if (is_null($this->corp_id)) {
                $this->corp_id = get_corp_id($this->corp, $this->alliance_id);
            }
            if (is_null($this->pilot_id)) {
                $this->pilot_id = get_pilot_id($this->pilot, $this->corp_id);
            }
            if (is_null($this->char_id)) {
                $this->char_id = get_pilot_charid($this->pilot_id);
            }
            if (is_null($this->ship_id)) {
                $this->ship_id = get_item_id($this->ship);
            }
            if (is_null($this->group_id)) {
                $this->group_id = get_item_group_id($this->ship_id);
            }
            if (is_null($this->weapon_id)) {
                $this->weapon_id = get_item_id($this->weapon);
            }
        }

        function reverse_ids() {
            if (! is_null($this->pilot_id)) {
                $this->pilot = get_pilot_name($this->pilot_id);
            }
            if (! is_null($this->alliance_id)) {
                $this->alliance = get_alliance_name($this->alliance_id);
            }
            if (! is_null($this->corp_id)) {
                $this->corp = get_corp_name($this->corp_id);
                $this->corp_ticker = get_corp_ticker($this->corp_id, 1);
            }
            if (! is_null($this->ship_id)) {
                $this->ship = get_item_name($this->ship_id);
            }
            if (! is_null($this->group_id)) {
                $this->group = get_group_name($this->group_id);
            }
            if (! is_null($this->weapon_id)) {
                $this->weapon = get_item_name($this->weapon_id);
            }
        }

        function img32() {
            global $_WEB_URL;
            return "<img align='left' src='$_WEB_URL/img/shiptypes/32_32/" .
                   $this->ship_id . ".png' alt='" . $this->ship . "' />";
        }

        function img64() {
            global $_WEB_URL;
            return "<img style='margin-right: 2px;' align='left' src='$_WEB_URL/img/shiptypes/64_64/" .
                   $this->ship_id . ".png' alt='" . $this->ship . "' />";
        }

        function img128() {
            global $_WEB_URL;
            return "<img style='margin-right: 2px;' align='left' src='$_WEB_URL/img/shiptypes/128_128/" .
                   $this->ship_id . ".png' alt='" . $this->ship . "' />";
        }

        function img256() {
            global $_WEB_URL;
            return "<img style='margin-right: 2px;' align='left' src='$_WEB_URL/img/shiptypes/256_256/" .
                   $this->ship_id . ".png' alt='" . $this->ship . "' />";
        }

        function pilot_img64() {
            if ($this->char_id) {
                $id = $this->char_id;
            } else {
                $id = 0;
            }
            return "<img align='left' src='http://img.eve.is/serv.asp?s=64&c=$id' alt='" . $this->pilot . "' />";
        }

        function pilot_img128() {
            if ($this->char_id) {
                $id = $this->char_id;
            } else {
                $id = 0;
            }
            return "<img align='left' src='http://img.eve.is/serv.asp?s=128&c=$id' alt='" . $this->pilot . "' />";
        }

        function pilot_img256() {
            if ($this->char_id) {
                $id = $this->char_id;
            } else {
                $id = 0;
            }
            return "<img align='left' src='http://img.eve.is/serv.asp?s=256&c=$id' alt='" . $this->pilot . "' />";
        }

        function pilot_link() {
            if ($this->pilot_id && $this->pilot) {
                global $_WEB_URL;
                $url = "$_WEB_URL/show_pilot.php?pilotid=" . $this->pilot_id;
                return "<a href='$url'>" . $this->pilot . "</a>";
            }
        }
        function alliance_link() {
            if ($this->alliance_id && $this->alliance) {
                global $_WEB_URL;
                $url = "$_WEB_URL/show.php?allianceid=" . $this->alliance_id;
                return "<a href='$url'>" . $this->alliance . "</a>";
            }
        }
        function ticker_link() {
            if ($this->corp_id && $this->corp) {
                global $_WEB_URL;
                if (! $this->corp_ticker) {
                    $this->corp_ticker = get_corp_ticker($this->corp_id);
                    $ticker = $this->corp_ticker;
                    if (! $this->corp_ticker) {
                        $ticker = "????";
                    } else {
                        # we actually got one, so we should nuke the cache for this kill
# FIXME HOW
                    }
                } else {
                    $ticker = $this->corp_ticker;
                }
                $url = "$_WEB_URL/show.php?corpid=" . $this->corp_id;
                return "<a href='$url'>" . $ticker . "</a>";
            }
        }
        function corp_link() {
            if ($this->corp_id && $this->corp) {
                global $_WEB_URL;
                $url = "$_WEB_URL/show_corp.php?corpid=" . $this->corp_id;
                return "<a href='$url'>" . $this->corp . "</a>";
            }
        }
        function weapon_link() {
            if ($this->weapon_id && $this->weapon) {
                global $_WEB_URL;
                $url = "$_WEB_URL/show.php?weaponid=" . $this->weapon_id;
                return "<a href='$url'>" . $this->weapon . "</a>";
            }
        }
        function group_link() {
            if ($this->group_id && $this->group) {
                global $_WEB_URL;
                $url = "$_WEB_URL/show.php?groupid=" . $this->group_id;
                return "<a href='$url'>" . $this->group . "</a>";
            }
        }
        function ship_link() {
            if ($this->ship_id && $this->ship) {
                global $_WEB_URL;
                $url = "$_WEB_URL/show.php?shipid=" . $this->ship_id;
                return "<a href='$url'>" . $this->ship . "</a>";
            }
        }
    }

    # our parser class
    class KillMail {
        var $kill_id = null;
        var $victim = null;
        var $killer = null;
        var $attackers = array();
        var $system = null;
        var $system_id = null;
        var $constellation = null;
        var $constellation_id = null;
        var $region = null;
        var $region_id = null;
        var $security = null;
        var $parsed = false;
        var $destroyed = array();
        var $date = null;
        var $killtime = null;
        var $raw = null;
        var $raw_id = null;

        function KillMail($mail = null, $mailid = null) {
            # can't do nothing with no mail :P
            if (is_null($mail)) {
                return;
            }

            # pre-pre-processing and recording
            $mail = trim($mail);
            $this->raw = $mail;
            $this->raw_id = $mailid;

            # our state machine, fairly simple...
            #   1 - processing the victim
            #   2 - processing attackers
            #   3 - processing destroyed items
            $state = 1;
            $this->victim = new Party;
            $attacker = null; # current attacker

            # try to prep the mail and parse it line by line
	        $lines = explode("\n", str_replace("<br>", "\n", $mail));
            foreach ($lines as $line) {
                $line = trim($line);
                if ($line == '') {
                    continue;
                }

                $arr = preg_split("/:\s*/", $line);
                if (count($arr) == 2) {
                    $key = strtolower($arr[0]);
                    $val = $arr[1];
                }

                switch ($state) {
                    case 1:
                        if (substr($line, 0, 5) == "Invol") {
                            $state = 2;
                        } elseif (preg_match("/^\d\d\d\d\.\d\d\.\d\d\s+\d\d:\d\d(?::\d\d)?$/", $line)) {
                            # and convert to a format MySQL can recognize later :)
                            $this->date = preg_replace("/\./", "-", $line) . ":00";
                        } else {
                            if ($key == 'system' || $key == 'solar system') {
                                $this->system = $val;
                            } elseif ($key == 'security' || $key == 'system security level') {
                                $this->security = $val;
                            } else {
                                $this->update_object(&$this->victim, $key, $val);
                            }
                        }
                        break;

                    case 2:
                        if (substr($line, 0, 5) == "Destr") {
                            if ($attacker) {
                                array_push($this->attackers, $attacker);
                                if ($attacker->finalblow) {
                                    $this->killer = $attacker;
                                }
                                $attacker = null;
                            }
                            $state = 3;
                        } else {
                            if ($key == "name") {
                                if ($attacker) {
                                    array_push($this->attackers, $attacker);
                                    if ($attacker->finalblow) {
                                        $this->killer = $attacker;
                                    }
                                }
                                $attacker = new Party();
                            }
                            $this->update_object(&$attacker, $key, $val);
                        }
                        break;

                    case 3:
                        array_push($this->destroyed, $line);
                        break;
                }
            }

            # if we have any hanging about attackers...
            if ($attacker) {
                array_push($this->attackers, $attacker);
                if ($attacker->finalblow) {
                    $this->killer = $attacker;
                }
            }

            # okay now remove attackers that we can't use :/
            # FIXME DO THIS?

            # try to get a date
            global $ft;
            if ($this->date) {
                $this->killtime = $ft->dbh->_select_one('SELECT UNIX_TIMESTAMP(?)', array($this->date));
#            } else {
# I changed my mind, you MUST provide a date. :/
#                $this->killtime = $ft->dbh->_select_one('SELECT UNIX_TIMESTAMP()');
            }

            # if we got here, we're good if we have the following bits of data
            if ($this->system && $this->security && $this->victim && count($this->attackers) > 0 && $this->killtime) {
                $this->parsed = true;
            }
            if (! $this->parsed) {
                return;
            }

            # process our destroyed list; note that we must have gotten here with
            # a successful parse so far, so let's try to parse the items
            $this->parsed = $this->process_destroyed();
        }

        function system_link() {
            if ($this->system_id && $this->system) {
                global $_WEB_URL;
                $url = "$_WEB_URL/show.php?systemid=" . $this->system_id;
                return "<a href='$url'>" . $this->system . "</a>";
            }
        }

        function constellation_img256() {
            global $_WEB_URL;
            return "<img align='left' src='$_WEB_URL/img/constellation/" .
                   $this->constellation_id . "___2_3_08256.png' alt='" . $this->constellation . "' />";
        }

        function region_img256() {
            global $_WEB_URL;
            return "<img align='left' src='$_WEB_URL/img/region/" .
                   $this->region_id . "___1_3_08256.png' alt='" . $this->region . "' />";
        }

        function system_img256() {
            global $_WEB_URL;
            return "<img align='left' src='$_WEB_URL/img/solarsystem/" .
                   $this->system_id . "___3_4_08_0256.png' alt='" . $this->system . "' />";
        }

        function constellation_link() {
            if ($this->constellation_id && $this->constellation) {
                global $_WEB_URL;
                $url = "$_WEB_URL/show.php?constellationid=" . $this->constellation_id;
                return "<a href='$url'>" . $this->constellation . "</a>";
            }
        }

        function region_link() {
            if ($this->region_id && $this->region) {
                global $_WEB_URL;
                $url = "$_WEB_URL/show.php?regionid=" . $this->region_id;
                return "<a href='$url'>" . $this->region . "</a>";
            }
        }

        # general key/value handler for victims and attackers
        function update_object(&$obj, $key, $val) {
            switch (substr($key, 0, 5)) {
                case "victi":
                case "name":
                    if (preg_match("/ \(laid the final blow\)$/", $val)) {
                        $val = preg_replace("/ \(laid the final blow\)$/", "", $val);
                        $obj->finalblow = true;
                    }
                    $obj->pilot = $val;
                    break;
                case "allia":
                    $obj->alliance = $val;
                    break;
                case "corp":
                case "corpo":
                    $obj->corp = $val;
                    break;
                case "destr":
                case "ship":
                case "ship ":
                    $obj->ship = $val;
                    break;
                case "secur":
                    $obj->security = $val;
                    break;
                case "weapo":
                    $obj->weapon = $val;
                    break;
            }
        }

        function process_destroyed_old() {
            $out = array();
            $curitem = null;

            foreach ($this->destroyed as $line) {
                $line = trim($line);
                if ($line == "") {
                    continue;
                }

                # try to find the data we want
                preg_match("/^(\w+):\s*(.+?)(?:\s+\((.+?)(?:\s+.+?)?\))?$/", $line, $matches);
                $type = strtolower($matches[1]);
                $rest = $matches[2];
                $extra = strtolower($matches[3]);

                if ($type == "type") {
                    if (! is_null($curitem)) {
                        array_push($out, $curitem);
                        $curitem = null;
                    }

                    # put this one together
                    $curitem = new Item();
                    $curitem->item = $rest;
                    if ($extra == "cargo") {
                        $curitem->loc = "cargo";
                    } elseif ($extra == "fitted") {
                        $curitem->loc = "fitted";
                    } elseif ($extra == "drone") {
                        $curitem->loc = "dronebay";
                    } else {
                        # wtf?
                        return false;
                    }

                } elseif ($type == "quantity") {
                    $curitem->quantity = $rest+0;
                    if ($curitem->quantity <= 0) {
                        $curitem->quantity = 1;
                    }
                }
            }

            # push on any item left
            if (! is_null($curitem)) {
                array_push($out, $curitem);
            }
            $this->destroyed = $out;

            # got here, must be done
            return true;
        }

        function process_destroyed() {
            if (count($this->destroyed) <= 0) {
                return true;
            }

            # create items out of our destroyed list, first see if this is old format
            if (substr($this->destroyed[0], 0, 5) == 'Type:') {
                return $this->process_destroyed_old();
            }

            $out = array();
            $curitem = null;

            foreach ($this->destroyed as $line) {
                $line = trim($line);
                if ($line == "") {
                    continue;
                }

                # try to find the data we want
                preg_match("/^(.+?)(?:,\s*Qty:\s*(\d+))?(?:\s*\((.+?)\))?$/", $line, $matches);
                $item = $matches[1];
                $qty = $matches[2]+0;
                $extra = strtolower($matches[3]);

                # put this one together
                $curitem = new Item();
                $curitem->item = $item;
                $curitem->quantity = $qty;
                if ($curitem->quantity <= 0) {
                    $curitem->quantity = 1;
                }
                if ($extra == "cargo") {
                    $curitem->loc = "cargo";
                } elseif ($extra == "drone bay") {
                    $curitem->loc = "dronebay";
                } else {
                    $curitem->loc = "fitted";
                }

                array_push($out, $curitem);
                $curitem = null;
            }

            # push on any item left
            if (! is_null($curitem)) {
                array_push($out, $curitem);
            }
            $this->destroyed = $out;

            # got here, must be done
            return true;

        }

        # store us to the database
        function store($userid = null) {
            if (! $this->parsed) {
                return;
            }

            global $ft, $remote;
            if (is_null($userid) && $remote) {
                $userid = $remote->userid();
            }
            if (is_null($userid)) {
                return;
            }

            $ft->dbh->_do_query('INSERT INTO tbl:rawmail (pilotid, submittime, submitip, mail) VALUES (?, UNIX_TIMESTAMP(), ?, ?)',
                                array($userid, $_SERVER['REMOTE_ADDR'], $this->raw));
            $this->raw_id = mysql_insert_id($ft->dbh->_dbh());
            return;
        }

        # ensure people/things we need are in the database
        function reverse_ids() {
            $sys = new EVESystem( $this->system_id );
            $this->system = $sys->getName;
            $this->security = $sys->getSecurity();
            $this->region = $sys->getRegionName();
            $this->constellation = $sys->getConstellationName();

            $this->victim->reverse_ids();
            $this->killer->reverse_ids();
            foreach (array_keys($this->destroyed) as $k) {
                $this->destroyed[$k]->reverse_ids();
            }
            foreach (array_keys($this->attackers) as $k) {
                $this->attackers[$k]->reverse_ids();
            }
        }

        # ensure people/things we need are in the database
        function get_ids() {
            $sys = new EVESystem( EVESystem::getId( $this->system ) );
            $this->system_id = $sys->getId();
            $this->region_id = $sys->getRegionId();
            $this->constellation_id = $sys->getConstellationId();

            if ($this->victim) {
                $this->victim->get_ids();
            }
            if ($this->killer) {
                $this->killer->get_ids();
            }
            foreach (array_keys($this->destroyed) as $k) {
                $this->destroyed[$k]->get_ids();
            }
            foreach (array_keys($this->attackers) as $k) {
                $this->attackers[$k]->get_ids();
            }
        }

        function &killer() {
            $final = null;
            foreach (array_keys($this->attackers) as $k) {
                if ($this->attackers[$k]->finalblow) {
                    $final =& $this->attackers[$k];
                }
            }
            if (! $final) {
                return null;
            }
            return $final;
        }

        function figure_killtype() {
            global $ft;

            # get the final killer
            $final = $this->killer();
            $killer_friendly = get_corp_allowed($final->corp_id);
            $victim_friendly = get_corp_allowed($this->victim->corp_id);

            # the states
            if ($killer_friendly && ! $victim_friendly) {
                return 'kill';
            } elseif ($killer_friendly && $victim_friendly) {
                return 'murder';
            } elseif ($victim_friendly) {
                return 'loss';
            } else {
                return 'other';
            }
            return 'other';
## END ##

            # get the data to use
            global $_ALLIANCE_STANDINGS, $_CORP_STANDINGS;
            if (! isset($_ALLIANCE_STANDINGS)) {
#                echo("[[ SET ALLIANCE STANDINGS ]]<br />\n");
                $alliances = $ft->dbh->_select_rows_as_objects('SELECT allianceid, standings FROM tbl:alliances');
                $_ALLIANCE_STANDINGS = $alliances;
            } else {
                $alliances = $_ALLIANCE_STANDINGS;
            }
            if (! isset($_CORP_STANDINGS)) {
#                echo("[[ SET CORP STANDINGS ]]<br />\n");
                $corps = $ft->dbh->_select_rows_as_objects('SELECT corpid, standings FROM tbl:corps');
                $_CORP_STANDINGS = $corps;
            } else {
                $corps = $_CORP_STANDINGS;
            }

            # now, get our standings
            $v_a = 0; $k_a = 0;
            $v_c = 0; $k_c = 0;
            foreach ($alliances as $a) {
                if ($a->allianceid == $this->victim->alliance_id) {
                    $v_a = $a->standings;
                }
                if ($a->allianceid == $final->alliance_id) {
                    $k_a = $a->standings;
                }
            }
            foreach ($corps as $c) {
                if ($c->corpid == $this->victim->corp_id) {
                    $v_c = $c->standings;
                }
                if ($c->corpid == $final->corp_id) {
                    $k_c = $c->standings;
                }
            }
#            echo("[[ (" . $this->victim->alliance_id . ',' . $this->victim->corp_id . ',' . $final->alliance_id . ',' . $final->corp_id . ") va=$v_a, vc=$v_c, ka=$k_a, kc=$k_c ]]<br />\n");

            # corp standings override alliance standings
            $killer_standings = $k_a;
            $victim_standings = $v_a;
            if ($k_c != 0) {
                $killer_standings = $k_c;
            }
            if ($v_c != 0) {
                $victim_standings = $v_c;
            }

            # figure out what happened
            if ($killer_standings > 0 && $victim_standings > 0) {
                return 'murder';
            } elseif ($killer_standings > 0 && $victim_standings <= 0) {
                return 'kill';
            } elseif ($killer_standings <= 0 && $victim_standings > 0) {
                return 'loss';
            } else {
                return 'other';
            }            
        }

        # using our information, try to ensure we don't have duplicates
        function is_duplicate() {
            $this->get_ids();

            $final = $this->killer();
            if (! $final) {
                return true;
            }

            global $ft;
            $res = $ft->dbh->_do_query('INSERT INTO tbl:dupeids (victimid, v_shipid, killerid, systemid, killtime) VALUES (?, ?, ?, ?, ?)',
                                       array($this->victim->pilot_id, $this->victim->ship_id,
                                             $final->pilot_id, $this->system_id, $this->killtime));
            if ($res) {
                # if the insert succeeds, we're not a duplicate
                return false;
            } else {
                return true;
            }
        }

        function increment_favorite(&$who, $which, $whichwhat, $amount = 1) {
            global $ft;
            $areas = array( 'pilot' => $who->pilot_id, 'corp' => $who->corp_id, 'alliance' => $who->alliance_id );
            foreach ($areas as $type => $id) {
                if (is_null($id) || ! is_numeric($id) || $id <= 0) {
                    continue;
                }

                $ft->dbh->_do_query("INSERT IGNORE INTO tbl:favorites (type, var1, ftype, var2, var3) VALUES (?, ?, ?, ?, 0)",
                                    array($type, $id, $which, $whichwhat));
                $ft->dbh->_do_query("UPDATE tbl:favorites SET var3 = var3 + $amount " .
                                    "WHERE type = ? AND var1 = ? AND ftype = ? AND var2 = ?",
                                    array($type, $id, $which, $whichwhat));
            }
        }

        function increment_stat(&$who, $year, $month, $week, $which, $amount = 1) {
            global $ft;
            $areas = array( 'pilot' => $who->pilot_id, 'corp' => $who->corp_id, 'alliance' => $who->alliance_id,
                            'system' => $this->system_id, 'weapon' => $who->weapon_id, 'group' => $who->group_id,
                            'region' => $this->region_id, 'constellation' => $this->constellation_id,
                            'ship' => $who->ship_id );
            foreach ($areas as $type => $id) {
                if (is_null($id) || ! is_numeric($id) || $id <= 0) {
                    continue;
                }

                $stats = array( 'year' => $year, 'month' => $month, 'week' => $week, 'ever' => 0 );
                foreach ($stats as $dtype => $when) {
                    $ft->dbh->_do_query("INSERT IGNORE INTO tbl:stats (type, var1, dtype, var2) VALUES (?, ?, ?, ?)",
                                        array($type, $id, $dtype, $when));
                    $ft->dbh->_do_query("UPDATE tbl:stats SET $which = $which + $amount " .
                                        "WHERE type = ? AND var1 = ? AND dtype = ? AND var2 = ?",
                                        array($type, $id, $dtype, $when));
                }
            }
        }

        # given the information in this killmail, recalculate some information
        function adjust_totals($override = 0) {
            $this->get_ids();

            # at this point we know we have all id information, we want to get a duplicate id
            if (! $override) {
                if ($this->is_duplicate()) {
                    return;
                }
            }

            # and now try to figure out what kind of kill this is
            $this->killtype = $this->figure_killtype();

            # not a dupe, let's add it to the summary table... this one is huge
            $final = $this->killer();
            if (! $final) {
                return false;
            }
            global $ft;
            $res = $ft->dbh->_do_query('INSERT INTO tbl:summary (v_pilotid, v_corpid, v_allianceid, v_shipid, ' .
                                       'k_pilotid, k_corpid, k_allianceid, k_security, k_shipid, systemid, ' .
                                       'system_security, killtime, weaponid, mailid, type, regionid, v_groupid, k_groupid, ' .
                                       'constellationid) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)',
                                       array($this->victim->pilot_id, $this->victim->corp_id, $this->victim->alliance_id,
                                             $this->victim->ship_id, $final->pilot_id, $final->corp_id, $final->alliance_id,
                                             $final->security, $final->ship_id, $this->system_id, $this->security,
                                             $this->killtime, $final->weapon_id, $this->raw_id, $this->killtype,
                                             $this->region_id, $this->victim->group_id, $final->group_id,
                                             $this->constellation_id));
            if (! $res ) {
                return false;
            }

            # now get the kill id back out
            $this->kill_id = mysql_insert_id($ft->dbh->_dbh());

            # figure out what day/month/year it is
            $matches = array();
            if (preg_match("/^(\d\d\d\d)-(\d\d)-(\d\d)\s/", $this->date, &$matches)) {
                $year = $matches[1];
                $month = $matches[2] + ($year * 100);
                $week = date("W", $this->killtime) + ($year * 100);
#                $day = $matches[3];
            }

            # hurrah, fo shizzle, insert our attacker rows
            foreach (array_keys($this->attackers) as $k) {
                $ki =& $this->attackers[$k];
                if ($ki->finalblow) {
                    $fb = 1;
                } else {
                    $fb = 0;
                }
                $ft->dbh->_do_query('INSERT INTO tbl:killers (killid, pilotid, corpid, allianceid, shipid, groupid, ' .
                                    'finalblow, weaponid, security) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)',
                                    array($this->kill_id, $ki->pilot_id, $ki->corp_id, $ki->alliance_id, $ki->ship_id,
                                          $ki->group_id, $fb, $ki->weapon_id, $ki->security));
            }

            # insert in items from this kill
            foreach (array_keys($this->destroyed) as $k) {
                $ki =& $this->destroyed[$k];
                $ft->dbh->_do_query('INSERT INTO tbl:killitems (killid, itemid, quantity, loc) VALUES (?, ?, ?, ?)',
                                    array($this->kill_id, $ki->item_id, $ki->quantity, $ki->loc));
            }

            # get some values of this kill
            $iskval = get_item_killpoints($this->victim->ship_id);
            $bounty = get_item_bountypoints($this->victim->ship_id);

            # do some stats on pilots, we're going to do this sort of condensation thing
            # now to save time later
            if ($this->killtype == 'kill' || $this->killtype == 'loss') {
                # a 'kill' is GOOD, we killed someone bad! yay!
                $this->increment_stat(&$this->victim, $year, $month, $week, $this->killtype . 'recv');
                if ($iskval > 0) {
                    $this->increment_stat(&$this->victim, $year, $month, $week, 'isklost', $iskval);
                }
                $this->increment_favorite(&$this->victim, 'ship_lost', $this->victim->ship_id);
                foreach (array_keys($this->attackers) as $k) {
                    # favorites on this pilot
                    $this->increment_favorite(&$this->attackers[$k], 'pilot', $this->victim->pilot_id);
                    $this->increment_favorite(&$this->attackers[$k], 'system', $this->system_id);
                    $this->increment_favorite(&$this->attackers[$k], 'weapon', $this->attackers[$k]->weapon_id);
                    $this->increment_favorite(&$this->attackers[$k], 'ship_killed', $this->victim->ship_id);
                    $this->increment_favorite(&$this->attackers[$k], 'ship_flown', $this->attackers[$k]->ship_id);

                    # stats on this pilot
                    $this->increment_stat(&$this->attackers[$k], $year, $month, $week, $this->killtype . 'give');
                    if ($this->attackers[$k]->finalblow) {
                        if ($iskval) {
                            $this->increment_stat(&$this->attackers[$k], $year, $month, $week, 'iskdestroyed', $iskval);
                        }
                        if ($bounty > 0) {
                            $this->increment_stat(&$this->attackers[$k], $year, $month, $week, 'bountypoints', $bounty);
                        }
                        if (count($this->attackers) == 1) {
                            # only one attacker, solo kill
                            $this->increment_stat(&$this->attackers[$k], $year, $month, $week, 'solokills');
                        }
                        $this->increment_stat(&$this->attackers[$k], $year, $month, $week, 'finalblows');
                    }
                }
            } elseif ($this->killtype == 'murder') {
                # Until a decision is made, you don't get final blow points for killing a friendly... also
                # you don't get isk destroyed, or isk lost, or anything... these are basically not in the stats
                # except as friendly kills                    
                $this->increment_stat(&$this->victim, $year, $month, $week, 'murderrecv');
                foreach (array_keys($this->attackers) as $k) {
                    $this->increment_stat(&$this->attackers[$k], $year, $month, $week, 'murdergive');
                }
            }
        }
    }

?>
