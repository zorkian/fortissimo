<?php

    include('lib/fortissimo.php');

    $ft->title('User Administration');

    if (! $remote) {
        return $ft->errorpage('You must be logged in to use this page.');
    }
    if (! $remote->director()) {
        return $ft->errorpage('You must be at least a director to use this page.');
    }

    # load users for list
    $users = $ft->dbh->_select_rows_as_objects('SELECT p.*, c.name AS "alliance" FROM tbl:corps p LEFT JOIN tbl:alliances c ' .
                                               'ON p.allianceid = c.allianceid ORDER BY p.name');

    # see what the remote is allowed to do and to whom
    $admin = 0; $manager = 0; $ceo = 0; $director = 0;
    if ($remote->admin()) {
        $admin = 1;
    }
    if ($admin || $remote->manager()) {
        $manager = 1;
    }
    if ($manager || $remote->ceo()) {
        $ceo = $remote->corpid();
    }
    if ($ceo || $remote->director()) {
        $director = $remote->corpid();
    }
    $rcorpid = $remote->corpid();

    # alliance level
    $out = array();
    if ($remote->manager() && $_POST['faid'] && ($note = $_POST['alliance_note_' . $_POST['faid']])) {
        $ft->dbh->_do_query('UPDATE tbl:alliances SET note = ? WHERE allianceid = ?',
                            array($note, $_POST['faid']));
        array_push($out, array('ok', null, 'Alliance note set.'));
    }

    # now iterate and find changes to make
    foreach ($users as $u) {
        if (! $_POST['ok_' . $u->corpid]) {
            # if they didn't get a row for this data, don't actually set things
            continue;
        }

        $cur_allowed = $u->allowed ? 1 : 0;
        $cur_warmode = $u->warmode + 0;
        $cur_ticker = $u->ticker;
        $cur_note = $u->note;
        $cur_standings = $u->standings;
        if (is_null($cur_standings)) {
            $cur_standings = -999;
        }

        if ($manager) {
            $set_allow = $_POST['allow_' . $u->corpid];
            if ($set_allow && ! $cur_allowed) {
                $ft->dbh->_do_query('UPDATE tbl:corps SET allowed = 1 WHERE corpid = ?',
                                    array($u->corpid));
                array_push($out, array('ok', $u, 'Corporation now ALLOWED to use this killboard.'));
            } elseif (! $set_allow && $cur_allowed) {
                if ($u->corpid == $remote->corpid()) {
                    array_push($out, array('err', $u, 'Disallowing that corporation would block your own access.  Dumbass.'));
                } else {
                    $ft->dbh->_do_query('UPDATE tbl:corps SET allowed = 0 WHERE corpid = ?',
                                        array($u->corpid));
                    array_push($out, array('ok', $u, 'Corporation NO LONGER allowed to use this killboard..'));
                }
            }

            $set_ticker = $_POST['ticker_' . $u->corpid];
            if ($set_ticker && $cur_ticker && $set_ticker != $cur_ticker) {
                $ft->dbh->_do_query('UPDATE tbl:corps SET ticker = ? WHERE corpid = ?',
                                    array($set_ticker, $u->corpid));
                array_push($out, array('ok', $u, "Corporation ticker changed to $set_ticker."));
            } elseif ($set_ticker && ! $cur_ticker) {
                $ft->dbh->_do_query('UPDATE tbl:corps SET ticker = ? WHERE corpid = ?',
                                    array($set_ticker, $u->corpid));
                array_push($out, array('ok', $u, "Corporation ticker now set to $set_ticker."));
            } elseif (! $set_ticker && $cur_ticker) {
                array_push($out, array('err', $u, 'Can\'t remove a ticker already set.  But you can change it if you want.'));
            }

            $set_standings = $_POST['standings_' . $u->corpid];
            if ($_POST['all_standings'] != -999) {
                $set_standings = $_POST['all_standings'];
            }
            if ($cur_standings != $set_standings) {
                $ft->dbh->_do_query('UPDATE tbl:corps SET standings = ? WHERE corpid = ?',
                                    array($set_standings, $u->corpid));
                array_push($out, array('ok', $u, "Corporation standings set to $set_standings."));
            }

            $set_note = $_POST['note_' . $u->corpid];
            if ($cur_note != $set_note) {
                $ft->dbh->_do_query('UPDATE tbl:corps SET note = ? WHERE corpid = ?',
                                    array($set_note, $u->corpid));
                array_push($out, array('ok', $u, "Corporation note set."));
            }
        }

        if ($manager || ($director && $director == $u->corpid)) {
            $set_warmode = $_POST['war_' . $u->corpid];
            if ($set_warmode && ($cur_warmode != $set_warmode)) {
                $ft->dbh->_do_query('UPDATE tbl:corps SET warmode = ? WHERE corpid = ?',
                                    array($set_warmode, $u->corpid));
                array_push($out, array('ok', $u, "Corporation entering $set_warmode hours delayed war mode."));
            } elseif (! $set_warmode && $cur_warmode) {
                $ft->dbh->_do_query('UPDATE tbl:corps SET warmode = 0 WHERE corpid = ?',
                                    array($u->corpid));
                array_push($out, array('ok', $u, 'Corporation taken OUT of war mode.'));
            }
        }
    }

    # that's the way the cookie crumbles
    $ft->assign('output', $out);
    $ft->makepage('admin_corps_do');

?>
