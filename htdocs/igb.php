<?php

    include('lib/fortissimo.php');

    # if not trusted, get trust
    global $_WEB_URL;
    if (! $ft->eve->TrustInit($_WEB_URL . '/', 'You must trust this Fortissimo killboard site in order to proceed.') ) {
        return $ft->igberrorpage("You must trust this site to use it.  Sorry!");
    }

    # get pilot's information
    $pilotid = get_pilot_with_info($ft->eve->CharId, $ft->eve->CharName, $ft->eve->CorpName, $ft->eve->AllianceName);
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
        return $ft->igberrorpage("Sorry, this killboard is not setup to allow your type to post here.");
    }

    # store output here
    $out = "<h2>Fortissimo Killboard</h2>";

    # do they have a password?
    if (! $obj->password()) {
        $out .= "<p>You do not have a password.  If you wish to use the out of game functionality ";
        $out .= "of this killboard, please enter a password to use and click the button.</p>";
        $out .= "<form method='post' action='$_WEB_URL/igb-password.php'>";
        $out .= "Password: <input type='password' name='password1' size='10' /><br />";
        $out .= "And again: <input type='password' name='password2' size='10' /><br />";
        $out .= "<input type='submit' value='Set Password' /></form>";
        $out .= "<h1>DO NOT USE YOUR EVE ACCOUNT PASSWORD. FOR ANY REASON. PERIOD.</h1><br /><br />";
    }

    # now give them a kill submission form
    $out .= "<h2>Killmail Submission</h2>";
    $out .= "<form method='post' action='$_WEB_URL/igb-submit.php'>";
    $out .= "Killmail here:<br /><textarea name='killmail' rows='10'></textarea>";
    $out .= "<input type='submit' value='Submit Mail' /></form>";

    # all done
    echo("<html><title>Fortissimo Killboard</title><body>$out</body></html>");

?>

