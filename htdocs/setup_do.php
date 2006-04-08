<?php

    include('lib/fortissimo.php');

    $ft->title('Fortissimo Setup');
    if ($ft->config('setup_done')) {
        return $ft->errorpage('Sorry, you have already completed the setup process.');
    }

    # STEP 1: let's create the tables that we need...
    include('lib/db_utils.php');
    setup_db();
    populate_db();

    # okay, make sure they gave us all the inputs
    $user = trim($_POST['admin_name']);
    $pw1 = trim($_POST['admin_pw1']);
    $pw2 = trim($_POST['admin_pw2']);
    $ft->assign('admin_name', $user);

    if (! $user || ! $pw1 || ! $pw2) {
        $ft->assign('error1', 'You must fill in all fields in this section.');
        return $ft->makepage('setup');
    }
    if ($pw1 != $pw2) {
        $ft->assign('error1', 'The chosen passwords do not match.');
        return $ft->makepage('setup');
    }
    if (strlen($pw1) < 6) {
        $ft->assign('error1', 'Your password must be at least 6 characters long.');
        return $ft->makepage('setup');
    }

    # setup this account
    $id = get_pilot_id($user);
    $ft->dbh->_do_query('UPDATE tbl:pilots SET password = ?, roles = 1 WHERE pilotid = ?', array($pw1, $id));
    $ft->set_config('setup_done', 1);

    # they're done
    $ft->assign('message', "Admin account <strong>$user</strong> created!");
    $ft->makepage('setup_do');

?>
