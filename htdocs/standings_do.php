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
        $what = substr($k, 0, 5);
        if ($what == 'corp_') {
            $id = substr($k, 5);
            if (is_numeric($id) && $id > 0) {
                if ($v == '' || $v == 0) {
                    $v = null;
                } elseif ($v < -100) {
                    $v = -100;
                } elseif ($v > 100) {
                    $v = 100;
                }
                $ft->dbh->_do_query("UPDATE tbl:corps SET standings = ? WHERE corpid = ?",
                        array($v, $id));
            }
        } else {
            $id = substr($k, 9);
            if (is_numeric($id) && $id > 0) {
                if ($v == '' || $v == 0) {
                    $v = null;
                } elseif ($v < -100) {
                    $v = -100;
                } elseif ($v > 100) {
                    $v = 100;
                }
                $ft->dbh->_do_query("UPDATE tbl:alliances SET standings = ? WHERE allianceid = ?",
                        array($v, $id));
            }
        }
    }

    $ft->message('Standings updated and saved.');
    $ft->title('Standings Editor');
    $ft->makepage('standings_do');

?>
