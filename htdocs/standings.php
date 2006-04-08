<?php

    include('lib/fortissimo.php');

    # load standings information
#    SELECT a.allianceid, a.name, a.standings, 
    
    $alliance = $ft->dbh->_select_rows_as_objects('SELECT allianceid, name, standings, note FROM tbl:alliances ORDER BY name');
    $corp = $ft->dbh->_select_rows_as_objects(
            'SELECT c.corpid, c.name, c.standings, c.allianceid, c.note
             FROM tbl:corps c, tbl:alliances a
             WHERE c.allianceid = a.allianceid
             ORDER BY a.name, c.name'
            );

    # create reference list
    $alliancenames = array();
    $alliancestandings = array();
    $alliancenotes = array();
    if ($alliance) {
        foreach ($alliance as $a) {
            $alliancenames[$a->allianceid] = $a->name;
            $alliancestandings[$a->allianceid] = $a->standings;
            $alliancenotes[$a->allianceid] = $a->note;
        }
    }
    $alliancenames[0] = "(no alliance)";
    $alliancestandings[0] = "0";

    # construct big list
    $sorted = array(); # each item is ( type, edit_name, real_name, standings )
    $lastallianceid = null;
    if ($corp) {
        foreach ($corp as $c) {
            $allianceid = 0;
            if (! is_null($c->allianceid) && $c->allianceid > 0) {
                $allianceid = $c->allianceid;
            }

            # if we haven't seen this alliance yet...
            if (is_null($lastallianceid) || $lastallianceid != $allianceid) {
                $lastallianceid = $allianceid;
                if ($allianceid > 0) {
                    array_push($sorted, array('alliance', "alliance_$allianceid",
                                              $alliancenames[$allianceid], $alliancestandings[$allianceid],
                                              $alliancenotes[$allianceid]));
                }
            }
            array_push($sorted, array('corp', 'corp_' . $c->corpid, $c->name, $c->standings, $c->note));
        }
    }

    # final fix
    $lastall = 0;
    $lastrow = null;
    $out = array();
    foreach ($sorted as $row) {
        if ($row[0] == 'corp' && (is_null($row[3]) || $row[3] == -999)) {
            continue;
        }

        if (is_null($lastrow)) {
            $lastrow = $row;
        } else {
            if ($row[0] == 'alliance') {
                if ($lastrow[0] == 'alliance') {
                    $lastrow = $row;
                } else {
                    array_push($out, $lastrow);
                    $lastrow = $row;
                }
            } else {
                array_push($out, $lastrow);
                $lastrow = $row;
            }
        }
    }
    if (! is_null($lastrow)) {
        if ($lastrow[0] != 'alliance') {
            array_push($out, $lastrow);
        }
    }

    # give them a list of standings to use
    $ft->assign('standings', array( 'unset' => -999,
                                    '10.0' => 100, '9.0' => 90, '7.5' => 75, '6.0' => 60, '2.5' => 25, '0.0' => 0,
                                    '-2.5' => -25, '-5.0' => -50, '-9.0' => -90, '-10.0' => -100 ));

    # now assign and render
    $ft->title('Standings Viewer');
    $ft->assign('sorted', $out);
    $ft->makepage('standings');

?>
