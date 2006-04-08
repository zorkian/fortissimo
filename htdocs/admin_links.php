<?php

    include('lib/fortissimo.php');

    if (! $remote) {
        return $ft->errorpage('You must be logged in to use this page.');
    }
    if (! $remote->admin()) {
        return $ft->errorpage('You must be an administrator to use this page.');
    }

    $ft->title('Administrate Links');
    $ft->makepage('admin_links');

?>
