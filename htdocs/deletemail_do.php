<?php

    include('lib/fortissimo.php');

    if (! $remote) {
        return $ft->errorpage('You must be logged in to use this page.');
    }
    if (! $remote->admin()) {
        return $ft->errorpage('Sorry, you must be an administrator to use this function.');
    }

    $raw = $_POST['raw']+0;
    $killid = $_POST['killid']+0;
    if ($raw) {
        $ft->dbh->_do_query('UPDATE tbl:rawmail SET expunged = 1 WHERE mailid = ?', array($killid));
    }

    # now back out the kill as best we can
    
    
    $ft->title('Delete Kill');
    $ft->makepage('deletemail_do');

?>
