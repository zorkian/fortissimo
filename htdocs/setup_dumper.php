<?php

    # include the module
    include('lib/fortissimo.php');

    # dump some data
    $which = $_GET['table'];
    $outarry = array();

    # see what they're after
    switch ($which) {
        case 'regions':
            $outarry = $ft->dbh->_select_rows_as_array('SELECT regionid, name FROM evedev_regions ORDER BY name');
            break;
        case 'constellations':
            $outarry = $ft->dbh->_select_rows_as_array('SELECT constellationid, name FROM evedev_constellations ORDER BY name');
            break;
        case 'systems':
            $outarry = $ft->dbh->_select_rows_as_array('SELECT systemid, regionid, constellationid, name, security ' .
                                                       'FROM evedev_systems ORDER BY name');
            break;
        case 'itemtypes':
            $outarry = $ft->dbh->_select_rows_as_array('SELECT typeid, groupid, name, baseprice, icon FROM evedev_itemtypes');
            break;
        case 'itemgroups':
            $outarry = $ft->dbh->_select_rows_as_array('SELECT groupid, categoryid, name FROM evedev_itemgroups');
            break;
    }

    # now output the array
    echo("# dumping $which...\n");
    if ($outarry) {
        foreach ($outarry as $line) {
            makeline($line);
        }
    }

    function makeline($arry) {
        $newarry = array();
        foreach ($arry as $item) {
            if (is_numeric($item)) {
                array_push($newarry, $item);
            } elseif (is_null($item)) {
                array_push($newarry, 'NULL');
            } else {
                array_push($newarry, "'" . mysql_real_escape_string($item) . "'");
            }
        }

        echo(implode(',', $newarry) . "\n");
    }

?>
