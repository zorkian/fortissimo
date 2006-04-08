<?php

    include('lib/fortissimo.php');

    $itemid = $_GET['itemid'];
    if (! is_numeric($itemid) || $itemid <= 0) {
        return $ft->errorpage('Sorry, you got here in an invalid way.');
    }

    # get the blueprint for this item
    $bpid = $ft->dbh->_select_one('SELECT blueprinttypeid FROM tbl:blueprints WHERE producttypeid = ?',
                                  array($itemid));
    if (! is_numeric($bpid) || $bpid <= 0) {
        return $ft->errorpage('Sorry, we couldn\'t find a blueprint for that object.');
    }

    # FIXME: get this from the database
    $cost = array(
        34 => 1,
        35 => 3,
        36 => 5.5,
        37 => 100,
        38 => 500,
        39 => 4000,
    );

    # populate the blueprint object
    $bpobj = new Item();
    $bpobj->item_id = $bpid;
    $bpobj->reverse_ids();

    # now get what goes into this
    $res = array();
    $total = 0;
    $mats = $ft->dbh->_select_rows_as_objects('SELECT m.* FROM tbl:manufacturing m, tbl:itemtypes i, tbl:itemgroups g ' .
                                              'WHERE m.typeid = ? AND i.typeid = m.requiredtypeid AND i.groupid = g.groupid AND g.categoryid != 16',
                                              array($bpobj->item_id));
    foreach ($mats as $mat) {
        $newobj = new Item();
        $newobj->item_id = $mat->requiredtypeid;
        $newobj->reverse_ids();

        $qty = $mat->quantity;
        $costpu = $cost[$mat->requiredtypeid];
        $qrow = $costpu*$qty;
        $total += $qrow;
        array_push($res, array($qty, $newobj, $costpu, $qrow));
    }

    # show the page
    $ft->assign('blueprint', $bpobj);
    $ft->assign('materials', $res);
    $ft->assign('total', $total);
    $ft->title('Item Viewer');
    $ft->makepage('show_item');

?>
