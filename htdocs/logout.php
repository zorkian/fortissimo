<?php

    include('lib/fortissimo.php');

    $ft->title('Logout');
    $ft->session->destroy();
    $ft->makepage('logout');

?>
