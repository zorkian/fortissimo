<?php

    include('lib/fortissimo.php');

    # show the last 50 somethings
    $which = $_GET['show'];
    if (! $which) {
        $which = 'kill';
    }
    if ($which == 'kill') {
        $ft->message('Last 50 Kills');
        $ft->title('Last 50 Kills');

        # also show the finalblows/lossrecv by group
        $groups = $ft->dbh->_select_rows_as_objects('SELECT var1, killrecv, lossrecv FROM tbl:stats ' .
                                                    'WHERE type = "group" AND dtype = "week" AND var2 = ?',
                                                    array(this_week()));
        $gout = array();
        if ($groups) {
            foreach ($groups as $grow) {
                $name = get_group_name($grow->var1);
                if ($name) {
                    $gout[$name] = array($grow->killrecv, $grow->lossrecv, $grow->var1);
                }
            }
        }
    } elseif ($which == 'loss') {
        $ft->message('Last 50 Losses');
        $ft->title('Last 50 Losses');
    } else {
        $which = 'murder';
        $ft->message('Last 50 Murders');
        $ft->title('Last 50 Murders');
    }
    $ids = $ft->dbh->_select_column('SELECT killid FROM tbl:summary WHERE type = ? ORDER BY killtime DESC LIMIT 50',
                                    array($which));

    # now prepare the page
    $kills = load_kills_by_id($ids);
    $ft->assign('summary', $gout);
    $ft->assign('classes', array('Battlecruiser', 'Destroyer',           'Elite Frigate',      'Industrial',
                                 'Battleship',    'Dreadnought',         'Elite Industrial',   'Mining Barge',
                                 'Capsule',       'Elite Battlecruiser', 'Elite Mining Barge', 'Mothership',
                                 'Carrier',       'Elite Cruiser',       'Freighter',          'Rookie Ship',
                                 'Cruiser',       'Elite Destroyer',     'Frigate',            'Shuttle'         ));
    $ft->assign('kills', $kills);
    $ft->assign('killids', $ids);

    # dump the page
    $ft->makepage('index');

?>
