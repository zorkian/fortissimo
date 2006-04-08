<?php

    include('lib/fortissimo.php');

    $killid = $_GET['killid'];
    if (is_null($killid) || ! is_numeric($killid) || $killid <= 0) {
        return $ft->errorpage('Sorry, it seems you got to this page in an invalid way.  Please try again.');
    }

    # if it's raw, fix it
    if ($_GET['raw']) {
        $mailid = $killid;
        $killid = $ft->dbh->_select_one('SELECT killid FROM tbl:summary WHERE mailid = ?', array($mailid));
    }

    # now load the kill
    $kills = load_kills_by_id(array($killid));
    $kill = $kills[$killid];

    # now dump it if we're raw
    if ($_GET['raw']) {
        if ($kill && hide_location($kill)) {
            $mail = "Sorry, this mail is secured due to war mode.  Please try again later.";
        } else {
            $mail = $ft->dbh->_select_one('SELECT mail FROM tbl:rawmail WHERE mailid = ?', array($mailid));
        }
        echo("<html><body><pre>$mail</pre></body></html>");
        exit;
    }

    # if admin, get more information
    if ($remote && $remote->manager()) {
        $info = $ft->dbh->_select_row_as_object('SELECT pilotid, FROM_UNIXTIME(submittime) AS "date", submitip ' .
                                                'FROM tbl:rawmail WHERE mailid = ?',
                                                array($kill->raw_id));
        $ft->assign('submitip', $info->submitip);
        $ft->assign('submittime', $info->date);
        $ft->assign('submitpilot', new Party($info->pilotid));
    }

    # get the kill itself
    $ft->assign('kill', $kill);

    # voila -- BAM

    $ft->title('Killmail Viewer');
    $ft->makepage('killmail');

?>
