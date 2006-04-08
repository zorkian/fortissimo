<?php

    include('lib/fortissimo.php');

    $ft->title('Fortissimo Setup');
    if ($ft->config('setup_done')) {
        return $ft->errorpage('Sorry, you have already completed the setup process.');
    }

    $ft->makepage('setup');

?>
