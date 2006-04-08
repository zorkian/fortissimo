<?php

    include('lib/fortissimo.php');

    if (! $remote) {
        return $ft->errorpage('You must be logged in to use this page.');
    }
    if (! $remote->admin()) {
        return $ft->errorpage('Sorry, you must be an administrator to use this function.');
    }

    $ft->assign('raw', $_GET['raw']);
    $ft->assign('killid', $_GET['killid']);
    $ft->title('Delete Kill');
    $ft->makepage('deletemail');

?>
