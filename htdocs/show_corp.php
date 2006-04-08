<?php

    include('lib/fortissimo.php');

    # figure out what they want to filter on
    $corpid = $_GET['corpid'];
    if (! $corpid) {
        return $ft->errorpage('Sorry, you got to this page in an invalid way.');
    }

    # load the pilot
    $corp = get_corp_name($corpid);
    if (! $corp) {
        return $ft->errorpage('Sorry, that corporation does not seem to exist.');
    }

    # now get some interesting stats
    $stats = array();
    $get = array( 'week' => this_week(), 'month' => this_month(), 'year' => this_year(), 'ever' => 0 );
    foreach ($get as $when => $val) {
        $st = $ft->dbh->_select_row_as_assoc('SELECT * FROM tbl:stats WHERE type = "corp" AND var1 = ? AND dtype = ? AND var2 = ?',
                                             array($corpid, $when, $val));
        $stats[$when] = $st;
    }

    # get various top 10s
    $ft->assign('t10weapons', get_top10('corp', $corpid, 'weapon'));
    $ft->assign('t10shipslost', get_top10('corp', $corpid, 'ship_lost'));
    $ft->assign('t10shipsdestroyed', get_top10('corp', $corpid, 'ship_killed'));
    $ft->assign('t10systems', get_top10('corp', $corpid, 'system'));
    $ft->assign('t10targets', get_top10('corp', $corpid, 'pilot'));
    $ft->assign('t10shipsflown', get_top10('corp', $corpid, 'ship_flown'));

    # get the last 50 final blows
#    $fbids = $ft->dbh->_select_column('SELECT killid FROM tbl:summary WHERE k_pilotid = ? AND type = "kill" ' .
#                                      'ORDER BY killtime DESC LIMIT 10', array($pilotid));
#    $fbs = load_kills_by_id($fbids);

    # us killing them
    $kids = $ft->dbh->_select_column('SELECT DISTINCT s.killid FROM tbl:killers k, tbl:summary s WHERE k.corpid = ? ' .
                                     'AND s.killid = k.killid AND s.type = "kill" ' .
                                     'ORDER BY s.killtime DESC LIMIT 10', array($corpid));
    $ks = load_kills_by_id($kids);

    # us losing to them
    $lids = $ft->dbh->_select_column('SELECT DISTINCT killid FROM tbl:summary WHERE v_corpid = ? AND type = "loss" ' .
                                     'ORDER BY killtime DESC LIMIT 10', array($corpid));
    $ls = load_kills_by_id($lids);

    # them killing us (FOR THEIR SIDE)
    $xkids = $ft->dbh->_select_column('SELECT DISTINCT killid FROM tbl:summary WHERE k_corpid = ? AND type = "loss" ' .
                                     'ORDER BY killtime DESC LIMIT 10', array($corpid));
    $xks = load_kills_by_id($xkids);

    # us killing them (FOR THEIR SIDE)
    $xlids = $ft->dbh->_select_column('SELECT DISTINCT s.killid FROM tbl:killers k, tbl:summary s WHERE k_corpid = ? ' .
                                      'AND s.killid = k.killid AND s.type = "kill" ' .
                                      'ORDER BY s.killtime DESC LIMIT 10', array($corpid));
    $xls = load_kills_by_id($xlids);


    # now, let's build this up, give them the information
    $ft->assign('iter', array('week', 'month', 'year', 'ever'));
    $ft->assign('headers', array( 'finalblows' => 'Final Blows',
                                  'solokills' => 'Solo Kills',
                                  'killgive' => 'Participations',
                                  'lossrecv' => 'Ships Lost',
                                  '1' => '',
                                  'murdergive' => 'People Murdered',
                                  'murderrecv' => 'Times Murdered',
                                  '2' => '',
                                  'bountypoints' => 'Bounty Taken',
                                  'iskdestroyed' => 'ISK Destroyed',
                                  'isklost' => 'ISK Lost',
                                   ));
    $ft->assign('corp', $corp);
    $ft->assign('corpid', $corpid);
    $ft->assign('stats', $stats);
    $ft->assign('killids2', $kids);
    $ft->assign('kills2', $ks);
    $ft->assign('lossids', $lids);
    $ft->assign('losses', $ls);
    $ft->assign('xkillids', $xkids);
    $ft->assign('xkills', $xks);
    $ft->assign('xlossids', $xlids);
    $ft->assign('xlosses', $xls);
    $ft->assign('finalblowids', $fbids);
    $ft->assign('finalblows', $fbs);

    # dump the page
    $ft->title('Corporation Statistics');
    $ft->makepage('show_corp');

?>
