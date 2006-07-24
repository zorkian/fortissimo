<?php

    class Session {
        var $user = null;

        function Session() {
            # load up a session
            $sesskey = $_COOKIE['FortissimoSession'];
            if (! $sesskey) {
                # they don't have one, so let's ignore
                return;
            }

            # try to parse their cookie
            $ct = sscanf($sesskey, "%d %s %d", $sessid, $auth, $exptime);

            # return if bad cookie, or they say it's expired, or the auth length is wrong or bad sessid
            if ($ct != 3 || $exptime < time() || strlen($auth) != 64 || $sessid <= 0) {
                return;
            }

            # okay, so try loading it
            global $ft;
            $row = $ft->dbh->_select_row_as_object('SELECT * FROM tbl:sessions WHERE sessid = ? AND sesskey = ?',
                                                   array($sessid, $auth));
            $userid = $row->userid;
            if (is_null($userid) || $userid <= 0 || $row->exptime < time()) {
                return;
            }

            # get the user this refers to
            $this->user = new User($userid);
        }

        # generate a new session
        function makenew($userid) {
            if (! $userid) {
                return false;
            }

            $key = $this->_gensesskey();
            $issuetime = time();
            $exptime = $issuetime + (60*60*24*7);

            global $ft;
            $ft->dbh->_do_query('INSERT INTO tbl:sessions (sesskey, userid, issueip, issuetime, exptime) VALUES (?, ?, ?, ?, ?)',
                                array($key, $userid, $_SERVER['REMOTE_ADDR'], $issuetime, $exptime));
            $obj = $ft->dbh->_select_row_as_object('SELECT * FROM tbl:sessions WHERE userid = ? AND sesskey = ?',
                                                   array($userid, $key));
            if ($obj) {
                $sessid = $obj->sessid;

                global $_COOKIE_PATH, $_COOKIE_DOMAIN;
                setcookie("FortissimoSession", "$sessid $key $exptime", $exptime, $_COOKIE_PATH, $_COOKIE_DOMAIN);

                global $remote;
                $remote = new User($userid);
                $ft->assign('remote', $remote);

                return $sessid;
            }

            return false;
        }

        # log a user out
        function destroy() {
            global $ft, $remote;
            $remote = null;
            $ft->assign('remote', $remote);

            global $_COOKIE_PATH, $_COOKIE_DOMAIN;
            setcookie("FortissimoSession", false, 0, $_COOKIE_PATH, $_COOKIE_DOMAIN);
        }

        # generate a random session key
        function _gensesskey() {
            # with compliments to "ripat at lumadis dot be" from php.net online docs
            $acceptedChars = 'azertyuiopqsdfghjklmwxcvbnAZERTYUIOPQSDFGHJKLMWXCVBN0123456789';
            $max = strlen($acceptedChars)-1;
            $key = null;
            for ($i = 0; $i < 64; $i++) {
                $key .= $acceptedChars{mt_rand(0, $max)};
            }
            return $key;
        }
    }

?>
