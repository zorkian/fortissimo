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
        $aextra = 'p.allianceid IS NULL';
    } elseif ($faid > 0) {
        $aextra = 'p.allianceid = ' . $faid;
    }
    $ft->assign('faid', $faid);

    # get alliances to filter by
    $alliances = $ft->dbh->_select_rows_as_objects('SELECT * FROM tbl:alliances ORDER BY name');

    # load users for list, everybody or just those in your corp?
    if ($remote->manager()) {
        $users = $ft->dbh->_select_rows_as_objects('SELECT p.*, c.name AS "alliance" FROM tbl:corps p LEFT JOIN tbl:alliances c ' .
                                                   "ON p.allianceid = c.allianceid WHERE $aextra ORDER BY p.name");
    } elseif ($remote->director()) {
        $users = $ft->dbh->_select_rows_as_objects('SELECT p.*, c.name AS "alliance" FROM tbl:corps p LEFT JOIN tbl:alliances c ' .
                                                   "ON p.allianceid = c.allianceid WHERE p.corpid = ? AND $aextra ORDER BY p.name",
                                                   array($remote->corpid()));
    } else {
        $users = array();
    }

    # break down by letters if there is more than 50 rows
    if (count($users) > 50) {
        $letters = array();
        foreach ($users as $u) {
            # auto-select something to filter by
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

    # standings
    $ft->assign('standings', array( 'unset' => -999,
                                    '10.0' => 100, '9.0' => 90, '7.5' => 75, '6.0' => 60, '2.5' => 25, '0.0' => 0,
                                    '-2.5' => -25, '-5.0' => -50, '-9.0' => -90, '-10.0' => -100 ));

    # just dump everything
    $ft->assign('alliances', $alliances);
    $ft->assign('users', $users);
    $ft->makepage('admin_corps');

?>
