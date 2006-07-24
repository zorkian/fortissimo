<?php

    # if not trusted, get trust
    global $_WEB_URL;
    if (! $ft->eve->TrustInit($_WEB_URL . '/', 'You must trust this Plexer site in order to proceed.') ) {
        return $ft->igberrorpage("You must trust this site to use it.  Sorry!");
    }

    # get pilot's information
    $pilotid = get_pilot_with_info($ft->eve->CharId, $ft->eve->CharName, $ft->eve->CorpName, $ft->eve->AllianceName);
    if (! $pilotid) {
        return $ft->igberrorpage("Sorry, unable to verify your account.  I cannot let you pass!");
    }

    # okay, they're in, do we know them?
    global $obj;
    $obj = new User($pilotid);
    if (! $obj) {
        return $ft->igberrorpage("Sorry, unable to access your account.  I cannot let you pass!");
    }

    # see what their standings are
    if (! $obj->trustable()) {
        return $ft->igberrorpage("Sorry, this site is not setup to allow your type to post here.");
    }

    $ok = 1;

?>
