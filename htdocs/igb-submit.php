<?php

    include('lib/fortissimo.php');

    # if not trusted, get trust
    global $_WEB_URL;
    if (! $ft->eve->TrustInit($_WEB_URL . '/', 'You must trust this Fortissimo killboard site in order to proceed.') ) {
        return $ft->igberrorpage("You must trust this site to use it.  Sorry!");
    }

    # see if we know them
    $corpid = get_corp_id($ft->eve->CorpName, 0);
    if (! $corpid) {
        return $ft->igberrorpage("Sorry, unable to verify your corporation.  I cannot let you pass!");
    }
    $pilotid = get_pilot_id($ft->eve->CharName, $corpid);
    if (! $pilotid) {
        return $ft->igberrorpage("Sorry, unable to verify your account.  I cannot let you pass!");
    }

    # okay, they're in, do we know them?
    $obj = new User($pilotid);
    if (! $obj) {
        return $ft->igberrorpage("Sorry, unable to access your account.  I cannot let you pass!");
    }

    # see what their standings are
    if (! $obj->trustable()) {
        return $ft->igberrorpage("Sorry, we don't allow your type here.");
    }

    # store output here
    $out = "<h2>Fortissimo Killboard</h2>";

    # get the mail
    $mail = $_POST['killmail'];
    if (! $mail) {
        return $ft->igberrorpage('You must provide a mail to parse.');
    }

    # try to parse
    $kobj = new KillMail($mail);
    if (! $kobj->parsed) {
        return $ft->igberrorpage('The killmail didn\'t parse.  Please ensure you copied the entire mail.');
    }

    # now make sure it's not a duplicate
    if ($kobj->is_duplicate()) {
        return $ft->igberrorpage('This seems to be a duplicate.  Sorry!');
    }

    # now put into database, since we know it's not a dupe
    $kobj->store($pilotid);
    $kobj->adjust_totals(1);

    # now show the success/error/etc
    $out = '<p>This kill of ' . $kobj->victim->pilot . '\'s ' . $kobj->victim->ship . ' has been recorded!  Thank you!</p>';

    # now give them a kill submission form
    $out .= "<h2>Killmail Submission</h2>";
    $out .= "<form method='post' action='$_WEB_URL/igb-submit.php'>";
    $out .= "Killmail here:<br /><textarea name='killmail' rows='10'></textarea>";
    $out .= "<input type='submit' value='Submit Mail' /></form>";

    # all done
    echo("<html><title>Fortissimo Killboard</title><body>$out</body></html>");

?>

