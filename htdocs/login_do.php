<?php

    include('lib/fortissimo.php');

    # error if already logged in
    if ($remote) {
        return $ft->errorpage('Sorry, you are already logged in.');
    }

    # attempt to get the information
    $username = $_POST['name'];
    $password = $_POST['password'];
    if (! $username || ! $password) {
        return $ft->errorpage('Sorry, please fill out both fields to log in.', 'login');
    }

    # try to load the pilot
    $user = new User($username);
    if (! $user || $user->password() != $password) {
        return $ft->errorpage('Sorry, invalid account and/or password.', 'login');
    }

    # make sure they're allowed to login now...
    if (! $user->admin()) {
        if (! get_corp_allowed($user->corpid())) {
            return $ft->errorpage('Sorry, your corporation is not allowed to use this killboard.', 'login');
        }
    }

    # now give them a session
    $ft->session->makenew($user->userid());

    # okay, they're logged in
    $ft->title('Logging In');
    $ft->makepage('login_do');

?>
