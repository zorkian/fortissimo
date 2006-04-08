<?php

    include('lib/fortissimo.php');

    $ft->title('User Administration');

    if (! $remote) {
        return $ft->errorpage('You must be logged in to use this page.');
    }
    if (! $remote->director()) {
        return $ft->errorpage('You must be at least a director to use this page.');
    }

    # see if we're filtering?
    $filter = $_GET['filter'];
    if (preg_match("/^[a-zA-Z0-9]$/", $filter)) {
        $filter = strtolower($filter);
        $ft->assign('filter', $filter);
    }

    # filter by alliance id too?
    $faid = $_GET['faid']+0;
    $aextra = '1';
    if ($faid == -1) {
        $aextra = 'p.corpid IS NULL';
    } elseif ($faid > 0) {
        $aextra = 'p.corpid = ' . $faid;
    }
    $ft->assign('faid', $faid);

    # get alliances to filter by
    $alliances = $ft->dbh->_select_rows_as_objects('SELECT * FROM tbl:corps ORDER BY name');

    # load users for list, everybody or just those in your corp?
    if ($remote->manager()) {
        $users = $ft->dbh->_select_rows_as_objects('SELECT p.*, c.name AS "corp" FROM tbl:pilots p, tbl:corps c ' .
                                                   "WHERE p.corpid = c.corpid AND $aextra ORDER BY p.name");
    } elseif ($remote->director()) {
        $users = $ft->dbh->_select_rows_as_objects('SELECT p.*, c.name AS "corp" FROM tbl:pilots p, tbl:corps c ' .
                                                   "WHERE p.corpid = c.corpid AND c.corpid = ? $aextra ORDER BY p.name",
                                                   array($remote->corpid()));
    } else {
        $users = array();
    }
    if (count($users) > 50) {
        $letters = array();
        foreach ($users as $u) {
            # auto select something?
            if ($u->name{0} != "") {
                $letters[strtoupper($u->name{0})] = 1;
            }
        }
        uksort($letters, 'cisort');
        $keys = array_keys($letters);
        $ft->assign('letters', $keys);
        if (is_null($filter) || $filter == "") {
            $filter = $keys[0];
            $ft->assign('filter', strtolower($filter));
        }
    }

    # now dump everything
    $ft->assign('alliances', $alliances);
    $ft->assign('users', $users);
    $ft->makepage('admin_users');

?>
