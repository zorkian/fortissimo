<?php

    include('lib/fortissimo.php');

    if (! $remote) {
        return $ft->errorpage('You must be logged in to use this page.');
    }

    $ft->makepage('order-index');

?>
