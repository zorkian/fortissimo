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

    # do they have a password?
    if ($obj->password()) {
        return $ft->igberrorpage("Sorry, you already have a password on your account.  If you need to reset your " .
                                 "password you must talk to an administrator.  Thank you!");
    }

    # store output here
    $out = "<h2>Fortissimo Killboard</h2>";

    # do they have a password?
    $pw1 = trim($_POST['password1']);
    $pw2 = trim($_POST['password2']);
    if ($pw1 != $pw2) {
        return $ft->igberrorpage("Password mismatch -- go back and type the same thing twice!");
    } elseif (strlen($pw1) < 6) {
        return $ft->igberrorpage("Password must be 6 or more characters long.");
    }
    $obj->password($pw1);

    # now give them a kill submission form
    $out .= "<p>Your password has been set.  Congratulations!  You can now use the out of game ";
    $out .= "killboard functionality.</p><p>You can go <a href='$_WEB_URL/igb.php'>submit kills</a> ";
    $out .= "now if you want.</p>";

    # all done
    echo("<html><title>Fortissimo Killboard</title><body>$out</body></html>");

?>

