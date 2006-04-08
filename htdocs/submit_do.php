<?php

    include('lib/fortissimo.php');

    if (! $remote) {
        return $ft->errorpage('You must be logged in to use this page.');
    }

    $mail = $_POST['mail'];
    if (! $mail) {
        return $ft->errorpage('You must provide a mail to parse.', 'submit');
    }

    $obj = new KillMail($mail);
    if (! $obj->parsed) {
        $ft->assign('mail', $mail);
        return $ft->errorpage('The killmail didn\'t parse.  Please ensure you copied the entire mail.', 'submit');
    }

    # now make sure it's not a duplicate
    if ($obj->is_duplicate()) {
        return $ft->errorpage('This seems to be a duplicate.  Sorry!', 'submit');
    }

    # now put into database, since we know it's not a dupe
    $obj->store();
    $obj->adjust_totals(1);

    # now show the success/error/etc
    $ft->message('This kill of ' . $obj->victim->pilot . '\'s ' . $obj->victim->ship . ' has been recorded!  Thank you!');
    $ft->title('Submit Killmail');
    $ft->makepage('submit');

?>
