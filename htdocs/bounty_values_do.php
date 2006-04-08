<?php

    include('lib/fortissimo.php');

    if (! $remote) {
        return $ft->errorpage('You must be logged in to use this page.');
    }

    if (! $remote->admin()) {
        return $ft->errorpage('Sorry, only administrators are allowed to use this page.');
    }

    # now load in the post values for ships
    foreach ($_POST as $k => $v) {
        $id = substr($k, 5);
        if (is_numeric($id) && $id > 0) {
            if ($v == '') {
                $v = null;
            }
            $ft->dbh->_do_query("UPDATE tbl:itemtypes SET bountypoints = ? WHERE typeid = ?",
                                array($v, $id));
        }
    }
    $ft->message('Bounty values updated and saved.');

    $ships = $ft->dbh->_select_rows_as_objects('SELECT s.typeid, s.name, s.bountypoints ' .
                                               'FROM tbl:itemtypes s, tbl:itemgroups g ' .
                                               'WHERE g.categoryid = 6 AND g.groupid = s.groupid AND s.seen = 1 ' .
                                               'ORDER BY g.name, s.name');
    $ft->assign('ships', $ships);

    $ft->title('Ship Bounty Value Editor');
    $ft->makepage('bounty_values');

?>
