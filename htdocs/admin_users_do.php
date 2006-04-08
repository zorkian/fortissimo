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
    $users = $ft->dbh->_select_rows_as_objects('SELECT p.*, c.name AS "corp" FROM tbl:pilots p, tbl:corps c ' .
                                               'WHERE p.corpid = c.corpid ORDER BY p.name');

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

    # now iterate and find changes to make
    $out = array();
    foreach ($users as $u) {
        if (! $_POST['ok_' . $u->pilotid]) {
            # if they didn't get a row for this data, don't actually set things
            continue;
        }

        if ($newpass = trim($_POST['pw_' . $u->pilotid])) {
            if ($director == $rcorpid) {
                if (strlen($newpass) < 6) {
                    array_push($out, array('err', $u, 'Password must be 6+ characters long.'));
                } else {
                    $ft->dbh->_do_query('UPDATE tbl:pilots SET password = ? WHERE pilotid = ?',
                                        array($newpass, $u->pilotid));
                    array_push($out, array('ok', $u, 'Password has been changed as requested.'));
                }
            } else {
                array_push($out, array('err', $u, 'You must be at least a Director to perform this action.'));
            }
        }

        $cur_admin = ($u->roles & 1) ? 1 : 0;
        $cur_ceo = ($u->roles & 2) ? 1 : 0;
        $cur_director = ($u->roles & 4) ? 1 : 0;
        $cur_manager = ($u->roles & 8) ? 1 : 0;

        if ($admin) {
            $set_admin = $_POST['admin_' . $u->pilotid];
            if ($set_admin && ! $cur_admin) {
                $ft->dbh->_do_query('UPDATE tbl:pilots SET roles = roles | 1 WHERE pilotid = ?',
                                    array($u->pilotid));
                array_push($out, array('ok', $u, 'Administrator flag turned ON for account.'));
            } elseif (! $set_admin && $cur_admin) {
                $ft->dbh->_do_query('UPDATE tbl:pilots SET roles = roles & ~1 WHERE pilotid = ?',
                                    array($u->pilotid));
                array_push($out, array('ok', $u, 'Administrator flag turned OFF for account.'));
            }
        }

        if ($admin) {
            $set_manager = $_POST['proc_' . $u->pilotid];
            if ($set_manager && ! $cur_manager) {
                $ft->dbh->_do_query('UPDATE tbl:pilots SET roles = roles | 8 WHERE pilotid = ?',
                                    array($u->pilotid));
                array_push($out, array('ok', $u, 'Manager flag turned ON for account.'));
            } elseif (! $set_manager && $cur_manager) {
                $ft->dbh->_do_query('UPDATE tbl:pilots SET roles = roles & ~8 WHERE pilotid = ?',
                                    array($u->pilotid));
                array_push($out, array('ok', $u, 'Manager flag turned OFF for account.'));
            }
        }

        if ($manager) {
            $set_ceo = $_POST['ceo_' . $u->pilotid];
            if ($set_ceo && ! $cur_ceo) {
                $ft->dbh->_do_query('UPDATE tbl:pilots SET roles = roles | 2 WHERE pilotid = ?',
                                    array($u->pilotid));
                array_push($out, array('ok', $u, 'CEO flag turned ON for account.'));
            } elseif (! $set_ceo && $cur_ceo) {
                $ft->dbh->_do_query('UPDATE tbl:pilots SET roles = roles & ~2 WHERE pilotid = ?',
                                    array($u->pilotid));
                array_push($out, array('ok', $u, 'CEO flag turned OFF for account.'));
            }
        }

        if ($manager || ($ceo && $ceo == $u->corpid)) {
            $set_director = $_POST['dir_' . $u->pilotid];
            if ($set_director && ! $cur_director) {
                $ft->dbh->_do_query('UPDATE tbl:pilots SET roles = roles | 4 WHERE pilotid = ?',
                                    array($u->pilotid));
                array_push($out, array('ok', $u, 'Director flag turned ON for account.'));
            } elseif (! $set_director && $cur_director) {
                $ft->dbh->_do_query('UPDATE tbl:pilots SET roles = roles & ~4 WHERE pilotid = ?',
                                    array($u->pilotid));
                array_push($out, array('ok', $u, 'Director flag turned OFF for account.'));
            }
        }
    }

    # that's the way the cookie crumbles
    $ft->assign('output', $out);
    $ft->makepage('admin_users_do');

?>
