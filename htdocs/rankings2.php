<?php

    include('lib/fortissimo.php');

    # show custom page?
    if ($_GET['custom']) {
        $ft->title('Rankings');
        $ft->assign('custom', 1);
        $ft->makepage('rankings');
        return;
    }
    $ft->assign('custom', 0);

    # what we're allowed
    $okby = array( 'killgive' => 'Enemies Killed', 'lossrecv' => 'Losses Taken',
                   'murdergive' => 'Friendlies Killed', 'murderrecv' => 'Losses to a Friendly',
                   'finalblows' => 'Final Blows', 'bountypoints' => 'Bounty Earned',
                   'iskdestroyed' => 'ISK Value Destroyed', 'isklost' => 'ISK Value Lost' );
    $okwhich = array( 'pilot' => 'by Pilot', 'corp' => 'by Corp', 'alliance' => 'by Alliance',
                      'system' => 'in System', 'weapon' => 'by Weapon', 'ship' => 'by Ship',
                      'region' => 'in Region', 'group' => 'by Ship Class' );

    # what we're doing
    $by = $_GET['by'];
    if (! $by) {
        $by = 'enemyskilled';
    }
    if (! isset($okby[$by])) {
        return $ft->errorpage('Invalid ranking criteria.');
    }
    $which = $_GET['which'];
    if (! $which) {
        $which = 'pilot';
    }
    if (! isset($okwhich[$which])) {
        return $ft->errorpage('Invalid ranking criteria.');
    }

    # now do the select
    $results = array();
    $maxct = 0;
    $v = array( 'week' => this_week(), 'month' => this_month(), 'year' => this_year(), 'ever' => 0 );
    foreach (array_keys($v) as $when) {
        $data = $ft->dbh->_select_rows_as_objects("SELECT var1, $by AS 'val' FROM tbl:stats WHERE type = ? AND dtype = ? AND var2 = ? " .
                                                  "AND $by > 0 ORDER BY $by DESC LIMIT 25",
                                                  array($which, $when, $v[$when]));
        if ($data) {
            # now convert the data to something useful
            $out = array();
            $ct = 0;
            foreach ($data as $row) {
                $ct++;
                if ($which == 'pilot') {
                    array_push($out, array(get_pilot_link($row->var1), $row->val));
                } elseif ($which == 'system') {
                    array_push($out, array(get_system_link($row->var1) . 
                                ' (<span style="color: ' . security_color(get_system_security($row->var1)) . ';">' .
                                security(get_system_security($row->var1)) . '</span>)', $row->val));
                } elseif ($which == 'corp') {
                    array_push($out, array(get_corp_link($row->var1), $row->val));
                } elseif ($which == 'alliance') {
                    array_push($out, array(get_alliance_link($row->var1), $row->val));
                } elseif ($which == 'region') {
                    array_push($out, array(get_region_link($row->var1), $row->val));
                } elseif ($which == 'group') {
                    array_push($out, array(get_group_link($row->var1), $row->val));
                } elseif ($which == 'ship') {
                    array_push($out, array(get_ship_link($row->var1), $row->val));
                } elseif ($which == 'weapon') {
                    array_push($out, array(get_item_link($row->var1), $row->val));
                }
            }
            if ($maxct < $ct) {
                $maxct = $ct;
            }
            $results[$when] = $out;
        } else {
            $results[$when] = array();
        }
    }

    # now prepare the page
    $ft->assign('data', $results);
    $ft->assign('which', $okwhich[$which]);
    $ft->assign('by', $okby[$by]);
    $ft->assign('whichorig', $which);
    $ft->assign('byorig', $by);
    $ft->assign('args', "which=$which&by=$by");
    $ctarray = array();
    for ($i = 0; $i < $maxct; $i++) {
        array_push($ctarray, $i);
    }
    $ft->assign('count', $ctarray);
    $ft->assign('order', array('week', 'month', 'year', 'ever'));

    # dump the page
    $ft->title('Rankings');
    $ft->makepage('rankings2');

?>
