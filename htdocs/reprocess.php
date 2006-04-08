<?php

    include('lib/fortissimo.php');

    if (! $remote) {
        return $ft->errorpage('You must be logged in to use this page.');
    }

    if (! $remote->admin()) {
        return $ft->errorpage('Sorry, only administrators can use this feature.');
    }

    $ft->title('Reprocess Mails');
    $ft->makepage('reprocess');

?>
