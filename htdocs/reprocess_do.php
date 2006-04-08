<?php

    include('lib/fortissimo.php');

    if (! $remote) {
        return $ft->errorpage('You must be logged in to use this page.');
    }

    if (! $remote->admin()) {
        return $ft->errorpage('Sorry, only administrators can use this feature.');
    }

    # get which one to start at
    $start = $_GET['start']+0;
    $rd = $_GET['round']+0;

    $ct = 0;
    $max = $start;
    $msg = "";
    $results = reprocess_all_mail($start);
    foreach ($results as $row) {
        $ct++;
        if ($row[0] == 'ok') {
            $msg .= "<span class='message'>$row[1]</span><br />\n";
        } else {
            $msg .= "<span class='error'>$row[1]</span><br />\n";
        }
        if ($row[2] > $max) {
            $max = $row[2];
        }
    }

    # redo this?
    if ($ct >= 10) {
        $max++;
        $rd++;
        $ft->assign('extrahead', "<meta http-equiv='refresh' content='0; /reprocess_do.php?start=$max&round=$rd' />");
    }

    # assign things
    $ft->assign('round', $rd);
    $ft->assign('start', $max);
    $ft->title('Reprocess Mails');
    $ft->assign('output', $msg);
    $ft->makepage('reprocess_do');

?>
